<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\TestsUtility;

class GuestsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->userAttributes = $this->utility->userAttributes();
    }

    public function test_any_guest_user_can_create_new_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $response = $this->postJson('register', $this->userAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1);

    }

    public function test_during_user_registration_user_details_have_been_created()
    {
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_details', 0);

        $resp = $this->postJson('register', $this->userAttributes);

        $resp->assertStatus(201);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('user_details', 1);
    }

    public function test_any_guest_user_can_login()
    {
        $this->assertDatabaseCount('users', 0);
        User::factory()->create(['email'=> 'test@email.com', 'password' => Hash::make('passwd')]);
        $this->assertDatabaseCount('users', 1);

        $response = $this->postJson('login', ['email'=>'test@email.com', 'password'=>'passwd']);
        $response->assertOk();
    }

    public function test_guest_user_cant_register_himself_twice_with_same_email()
    {
        $this->assertDatabaseCount('users', 0);
        User::factory()->create(['email' => $this->userAttributes['email']]);

        $response = $this->postJson('register', $this->userAttributes);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_guest_user_can_get_cities_list_for_application_form()
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

    public function test_guest_user_can_submit_application_form()
    {
        $lead = Lead::factory()->make(['name' => 'TestLead'])->toArray();
        $lead['area'] = 'ComuneTest, MI, Regione';
        $this->assertDatabaseCount('leads', 0);

        $resp = $this->postJson('api/lead', $lead);

        $resp->assertCreated();
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['name' => 'TestLead']);
    }

    public function test_guest_user_can_get_leads()
    {
        $verified_leads = Lead::factory()->count(10)->create(['approved' => true]);
        $unverified_leads = Lead::factory()->count(5)->create(['approved' => false]);
        $this->assertDatabaseCount('leads', 15);

        $resp = $this->getJson('api/leads');

        $resp->assertOk();
        $leads = json_decode($resp->getContent())->leads;
        $this->assertEquals(10, count($leads));
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
