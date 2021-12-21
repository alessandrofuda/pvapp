<?php

namespace Tests\Feature\guestTests;

use App\Models\Area;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\TestsUtility;

class LeadGenerationTest extends TestCase
{
    use RefreshDatabase;

    public TestsUtility $utility;
    public array $leadRequestAttributes;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->leadRequestAttributes = $this->utility->leadRequestAttributes();
    }

    public function test_guest_user_can_get_municipalities_list_for_application_form()
    {
        DB::table('areas')->insert([
            'region_code' => 1,
            'region_name' => 'Lombardia',
            'prov_code' => 2,
            'prov_abbr' => 'MI',
            'prov_name' => 'Milano',
            'city' => 'TestCity',
            'capoluogo' => 0
        ]);
        $this->assertDatabaseCount('areas', 1);

        $resp = $this->getJson('api/municipalities');

        $resp->assertOk();
        $resp->assertJson(['municipalities' => ['TestCity, MI, Lombardia'] ]);
    }

    public function test_guest_user_can_submit_lead_form()
    {
        $this->assertDatabaseCount('leads', 0);
        Area::factory()->create(['city'=>'City Test', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->assertDatabaseCount('areas', 1);

        $resp = $this->postJson('api/lead', $this->leadRequestAttributes);

        $resp->assertCreated();
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['name' => 'Pippo']);
    }

    public function test_guest_user_can_get_leads()
    {
        Lead::factory()->count(10)->create(['approved' => true]);
        Lead::factory()->count(5)->create(['approved' => false]);
        $this->assertDatabaseCount('leads', 15);

        $resp = $this->getJson('api/leads');

        $resp->assertOk();
        $leads = json_decode($resp->getContent())->leads;
        $this->assertEquals(10, count($leads));
    }

    public function test_guest_user_cant_submit_inexistent_city()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithFakeAreas = $this->leadRequestAttributes;
        $leadAttributesWithFakeAreas['area'] = 'Fake_City, MI, Lombardia';

        $resp = $this->postJson('api/lead', $leadAttributesWithFakeAreas);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_guest_user_cant_submit_wrong_city()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithFakeAreas = $this->leadRequestAttributes;
        $leadAttributesWithFakeAreas['area'] = 'Agliè, CZ, Lombardia';

        $resp = $this->postJson('api/lead', $leadAttributesWithFakeAreas);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_guest_user_cant_submit_inexistent_province()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithInexistentProvince = $this->leadRequestAttributes;
        $leadAttributesWithInexistentProvince['area'] = 'Cinisello Balsamo, ZZ, Lombardia';

        $resp = $this->postJson('api/lead', $leadAttributesWithInexistentProvince);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_guest_user_cant_submit_wrong_province()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithWrongProvince = $this->leadRequestAttributes;
        $leadAttributesWithWrongProvince['area'] = 'Cinisello Balsamo, BO, Lombardia';

        $resp = $this->postJson('api/lead', $leadAttributesWithWrongProvince);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_guest_user_cant_submit_inexistent_region()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithInexistentAreas = $this->leadRequestAttributes;
        $leadAttributesWithInexistentAreas['area'] = 'Fake_City, MI, Fake_Region';

        $resp = $this->postJson('api/lead', $leadAttributesWithInexistentAreas);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_guest_user_cant_submit_wrong_region()
    {
        $this->assertDatabaseCount('leads', 0);
        $leadAttributesWithWrongAreas = $this->leadRequestAttributes;
        $leadAttributesWithWrongAreas['area'] = 'Fake_City, MI, Fake_Region';

        $resp = $this->postJson('api/lead', $leadAttributesWithWrongAreas);

        $resp->assertStatus(422);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_if_guest_submit_lead_with_phone_that_already_exists_it_update_the_previous()
    {
        Lead::factory()->create(['phone'=>'123456789']);
        $this->assertDatabaseCount('leads', 1);
        Area::factory()->create(['city'=>'City Test', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->leadRequestAttributes['phone'] = '123456789';
        $this->leadRequestAttributes['name'] = 'new Name';

        $resp = $this->postJson('api/lead', $this->leadRequestAttributes);

        $resp->assertStatus(200);
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['phone'=>'123456789']);
        $this->assertDatabaseHas('leads', ['name'=>'new Name']);
    }

    public function test_if_guest_submit_lead_with_email_that_already_exists_it_update_the_previous()
    {
        Lead::factory()->create(['email'=>'email@test.com']);
        $this->assertDatabaseCount('leads', 1);
        Area::factory()->create(['city'=>'City Test', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->leadRequestAttributes['email'] = 'email@test.com';
        $this->leadRequestAttributes['name'] = 'new Name';

        $resp = $this->postJson('api/lead', $this->leadRequestAttributes);

        $resp->assertStatus(200);
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['email'=>'email@test.com']);
        $this->assertDatabaseHas('leads', ['name'=>'new Name']);
    }

    public function test_if_a_lot_of_submissions_in_same_day_block_request_with_rate_limiting()
    {
        $this->assertDatabaseCount('leads', 0);
        Area::factory()->create(['city'=>'City Test', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);

        $resp1 = $this->postJson('api/lead', $this->leadRequestAttributes);
        $resp1->assertCreated();
        $resp2 = $this->postJson('api/lead', $this->leadRequestAttributes);
        $resp2->assertOk();
        $resp3 = $this->postJson('api/lead', $this->leadRequestAttributes);
        $resp3->assertOk();
        $resp4 = $this->postJson('api/lead', $this->leadRequestAttributes);
        $resp4->assertStatus(429);

        $this->assertDatabaseCount('leads', 1);

    }

    public function test_throttling_block_too_many_submissions_from_same_ip() // defined 'leadSubmissions' in RouteServiceProvider
    {
        $this->assertDatabaseCount('leads', 0);
        Area::factory()->create(['city'=>'City Test', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->assertDatabaseCount('areas', 1);

        for ($i=0; $i<50; $i++) {
            $this->leadRequestAttributes['email'] = 'email@test'.$i.'.com';
            $this->leadRequestAttributes['phone'] = '11111111'.$i;

            $resp = $this->postJson('api/lead', $this->leadRequestAttributes);
            if($i < 5) {
                $resp->assertStatus(201);
            } else {
                $resp->assertStatus(429);
            }
        }
    }

//    public function test_guest_user_can_view_leads_page()
//    {
//        // todo frontend-side check/test
//    }
//
//    public function test_logged_user_cant_view_leads_page()
//    {
//        // todo frontend-side check/test
//    }
//
//    public function test_guest_user_view_leads_with_hidden_fields()
//    {
//        // todo frontend-side check/test
//    }





}
