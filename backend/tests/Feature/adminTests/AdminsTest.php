<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class AdminsTest extends TestCase
{
    use RefreshDatabase;

    protected TestsUtility $utility;
    protected Authenticatable $admin;
    protected array $userRequestAttributes;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->admin = $this->utility->createAdmin();
        $this->userRequestAttributes = $this->utility->userRequestAttributes();
    }

    public function test_admin_can_get_all_users()
    {
        $this->assertDatabaseCount('users', 1);
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/admin/users');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertCount(3+1, $content->users);
    }

    public function test_admin_can_create_new_user_profile()
    {
        $this->assertDatabaseCount('users', 1);
        $response = $this->actingAs($this->admin)->postJson('api/admin/users', $this->userRequestAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1+1);

    }

    public function test_admin_can_shows_each_singular_users_profiles()
    {
        $this->assertDatabaseCount('users', 1);
        $user = User::factory()->create(['id'=>5]);

        $resp = $this->actingAs($this->admin)->getJson('api/admin/users/'.$user['id']);

        $resp->assertOk();
        $this->assertDatabaseCount('users', 1+1);
    }

    public function test_only_admin_can_edit_all_users_profiles()
    {
        $this->assertDatabaseCount('users', 1);
        $user = User::factory()->create(['id'=>6, 'name'=>'name']);
        $this->assertDatabaseHas('users', ['name'=> 'name']);

        $userRequestAttributes = $this->userRequestAttributes;
        $userRequestAttributes['name'] = 'newName';

        $risp = $this->actingAs($this->admin)->putJson('api/admin/users/'.$user['id'], $userRequestAttributes);

        $risp->assertOk();
        $this->assertDatabaseHas('users', ['name'=>'newName']);

        $risp2 = $this->actingAs($this->utility->createOperator())->putJson('api/admin/users/'.$user['id'], $userRequestAttributes);
        $risp2->assertForbidden();
    }

    public function test_only_admin_can_delete_users_profiles()
    {
        $user = User::factory()->create(['id'=>8]);
        $risp = $this->actingAs($this->admin)->deleteJson('api/admin/users/'.$user['id']); // this create a record in users table

        $risp->assertOk();
        $this->assertDatabaseCount('users', 1);

        $risp2 = $this->actingAs($this->utility->createOperator())->deleteJson('api/admin/users/'.$user['id']); // this create a record in users table
        $risp2->assertForbidden();
        $this->assertDatabaseCount('users', 2);
    }

    public function test_admin_can_submit_application_form()
    {
        $lead = Lead::factory()->make()->toArray();
        $lead['area'] = 'Cinisello Balsamo, MI, Lombardia';
        $this->assertDatabaseCount('leads', 0);
        Area::factory()->create(['city'=>'Cinisello Balsamo', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->assertDatabaseCount('areas', 1);

        $resp = $this->actingAs($this->admin)->postJson('api/lead', $lead);

        $resp->assertCreated();
        $this->assertDatabaseCount('leads', 1);
    }

    public function test_admin_can_get_all_leads()
    {
        Lead::factory()->count(5)->create();
        $this->assertDatabaseCount('leads', 5);

        $resp = $this->actingAs($this->admin)->getJson('api/admin/leads');

        $resp->assertOk();
        $this->assertCount(5, json_decode($resp->getContent())->leads);
    }

    public function test_admin_can_create_a_lead()
    {
        $lead = Lead::factory()->make()->toArray();
        $lead['area'] = 'Cinisello Balsamo, MI, Lombardia';
        $this->assertDatabaseCount('leads', 0);
        Area::factory()->create(['city'=>'Cinisello Balsamo', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->assertDatabaseCount('areas', 1);

        $resp = $this->actingAs($this->admin)->postJson('api/admin/leads', $lead);

        $resp->assertCreated();
        $this->assertDatabaseCount('leads', 1);
    }

    public function test_admin_can_update_a_lead()
    {
        $lead_factory = Lead::factory();
        $lead_factory->create(['id' => 1, 'name' => 'lead1']);
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['name' => 'lead1']);
        $lead_attributes = $lead_factory->make()->toArray();
        $lead_attributes['area'] = 'Ciny, MI, Lombardia';
        $lead_attributes['name'] = 'UpdatedName';
        Area::factory()->create(['city'=>'Ciny', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);
        $this->assertDatabaseCount('areas', 1);

        $resp = $this->actingAs($this->admin)->putJson('api/admin/leads/1', $lead_attributes);

        $resp->assertOk();
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['name' => 'UpdatedName']);
        $this->assertDatabaseMissing('leads', ['name' => 'lead1']);
    }

    public function test_admin_can_get_a_single_lead()
    {
        Lead::factory()->create(['id' => 1]);
        Lead::factory()->create(['id' => 2]);
        $this->assertDatabaseCount('leads', 2);

        $resp = $this->actingAs($this->admin)->getJson('api/admin/leads/1');

        $resp->assertOk();
        $this->assertEquals(1, json_decode($resp->getContent())->lead->id);
    }

    public function test_admin_can_delete_a_lead()
    {
        Lead::factory()->create(['id' => 1]);
        Lead::factory()->create(['id' => 2]);
        $this->assertDatabaseCount('leads', 2);

        $resp = $this->actingAs($this->admin)->deleteJson('api/admin/leads/1');

        $resp->assertOk();
        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['id' => 2]);
        $this->assertDatabaseMissing('leads', ['id' => 1]);
    }

}
