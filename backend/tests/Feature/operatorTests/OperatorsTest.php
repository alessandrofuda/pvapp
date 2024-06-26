<?php

namespace Tests\Feature\operatorTests;

use App\Models\Area;
use App\Models\Lead;
use App\Models\User;
use App\Models\OperatorDetail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class OperatorsTest extends TestCase
{
    use RefreshDatabase;

    protected TestsUtility $utility;
    protected Authenticatable $operator;
    protected array $userRequestAttributes;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->operator = $this->utility->createOperator();
        $this->userRequestAttributes = $this->utility->userRequestAttributes();
    }

    public function test_operator_cant_get_all_users()
    {
        $this->assertDatabaseCount('users', 1);
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->operator)->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_operator_can_get_their_own_profile()
    {
        $this->assertDatabaseCount('users', 1);
        $operator2 = User::factory()->create(['name' => 'newName', 'role_id' => User::ROLE['operator']]);
        User::factory()->count(5)->create(['role_id' => User::ROLE['operator']]);
        $this->assertDatabaseCount('users', 7);

        $resp = $this->actingAs($operator2)->getJson('api/user');

        $resp->assertOk();
        $this->assertEquals('newName', json_decode($resp->getContent())->user->name);
    }

    public function test_operator_can_get_only_their_own_profile()
    {
        $user = User::factory()->create(['name' => 'name1', 'role_id' =>User::ROLE['operator']]);
        $user2 = User::factory()->create(['name' => 'name2', 'role_id' =>User::ROLE['operator']]);
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseHas('users', ['name' => 'name1']);
        $this->assertDatabaseHas('users', ['name' => 'name2']);

        $resp = $this->actingAs($user)->getJson('api/user');

        $resp->assertJson(['user' => ['name'=>'name1']]);
        $resp->assertJsonMissing(['user' => ['name'=>'name2']]);
    }

    public function test_operator_can_edit_only_their_own_profile()
    {
        $user  = User::factory()->create(['name'=>'user1','role_id'=>User::ROLE['operator']]); // id --> 2
        $user2 = User::factory()->create(['name'=>'user2','role_id'=>User::ROLE['operator']]); // id --> 3

        $this->userRequestAttributes['id'] = 3;
        $this->userRequestAttributes['name'] = 'newUserName';
        $resp = $this->actingAs($user)->putJson('api/user', $this->userRequestAttributes);

        $resp->assertOk();
        $this->assertEquals('newUserName', User::find(2)->name);
        $this->assertEquals('user2', User::find(3)->name);
        $this->assertDatabaseMissing('users', ['name' => 'user1']);

    }

    public function test_operator_cant_modify_their_own_id()
    {
        $user = User::factory()->create(['role_id'=>User::ROLE['operator']]);
        $originalUserId = $user->id;
        $this->userRequestAttributes['id'] = 99;
        $resp = $this->actingAs($user)->putJson('api/user', $this->userRequestAttributes);

        $resp->assertOk();
        $this->assertDatabaseMissing('users', ['id' => 99]);
        $this->assertDatabaseHas('users', ['id' => $originalUserId]);
    }

    public function test_operator_cant_edit_others_operators_profiles()
    {
        $user = User::factory()->create(['role_id' => User::ROLE['operator']]);
        $user2 = User::factory()->create(['role_id' => User::ROLE['operator']]);
        $this->assertEquals(2, $user->id);
        $this->assertEquals(3, $user2->id);

        $this->userRequestAttributes['id'] = 3;
        $this->userRequestAttributes['name'] = 'newName';

        $this->actingAs($user)->putJson('api/user', $this->userRequestAttributes);

        $this->assertNotEquals('newName', $user2->name);
    }

    public function test_each_operator_can_delete_their_own_profile()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['id'=>1]);

        $resp = $this->actingAs($this->operator)->deleteJson('api/user');

        $resp->assertOk();
        $this->assertDatabaseMissing('users', ['id' => 1]);
    }

    public function test_each_operator_can_delete_only_their_own_profile_even_if_passing_user_attributes()
    {
        User::factory()->create(['role_id' => User::ROLE['operator']]);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['id'=>1]);
        $this->assertDatabaseHas('users', ['id'=>2]);

        $this->userRequestAttributes['id'] = 2;
        $resp = $this->actingAs($this->operator)->deleteJson('api/user', $this->userRequestAttributes);

        $resp->assertOk();
        $this->assertDatabaseMissing('users', ['id' => 1]);
        $this->assertDatabaseHas('users', ['id' => 2]);
    }

    public function test_logged_in_operator_cant_submit_lead()
    {
        $lead = Lead::factory()->make()->toArray();
        $lead['area'] = 'Cinisello Balsamo, MI, Lombardia';
        Area::factory()->create(['city'=>'Cinisello Balsamo', 'prov_abbr'=>'MI', 'region_name'=>'Lombardia']);

        $resp = $this->actingAs($this->operator)->postJson('api/lead', $lead);

        $resp->assertForbidden();
        $this->assertDatabaseCount('leads', 0);

    }

    public function test_logged_in_operator_cant_update_lead()
    {
        $lead = Lead::factory()->make()->toArray();
        $lead['area'] = 'Cinisello Balsamo, MI, Lombardia';

        $resp = $this->actingAs($this->operator)->putJson('api/lead', $lead);

        $resp->assertStatus(405);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_operator_cant_delete_an_admin()
    {
        User::factory()->create(['role_id' => User::ROLE['admin']]);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['role_id' => User::ROLE['admin']]);
        $this->assertDatabaseHas('users', ['role_id' => User::ROLE['operator']]);

        $resp = $this->actingAs($this->operator)->deleteJson('api/user/');

        $resp->assertOk();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['role_id' => User::ROLE['admin']]);
        $this->assertDatabaseMissing('users', ['role_id' => User::ROLE['operator']]);
    }

    public function test_operator_can_get_their_own_details()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('operator_details', 1);
        $this->assertDatabaseHas('users', ['role_id' => User::ROLE['operator']]);
        $this->assertDatabaseHas('operator_details', ['user_id' => 1]);

        $resp = $this->actingAs($this->operator)->getJson('api/user');

        $operator_detail = json_decode($resp->getContent())->user->detail;
        $this->assertObjectHasAttribute('first_name', $operator_detail);
        $this->assertObjectHasAttribute('invoice_vat', $operator_detail);
    }

    public function test_operator_can_update_their_own_details()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('operator_details', 1);
        $this->assertDatabaseMissing('operator_details', ['invoice_vat' => 'IT08497200967']);
        $this->userRequestAttributes['invoice_vat'] = 'IT08497200967';

        $resp = $this->actingAs($this->operator)->putJson('api/user', $this->userRequestAttributes);

        $resp->assertOk();
        $this->assertDatabaseCount('operator_details', 1);
        $this->assertDatabaseHas('operator_details', ['invoice_vat' => 'IT08497200967']);
    }

    public function test_if_operator_delete_their_own_profile_operator_details_are_deleted_too()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('operator_details', 1);

        $resp = $this->actingAs($this->operator)->deleteJson('api/user');

        $resp->assertOk();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('operator_details', 0);
    }

}
