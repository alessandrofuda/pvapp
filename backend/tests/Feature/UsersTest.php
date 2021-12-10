<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected array $userAttributes;
    protected TestsUtility $utility;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->userAttributes = $this->utility->userAttributes();
    }

    public function test_user_cant_get_all_users()
    {
        $this->assertDatabaseCount('users', 0);
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->utility->createUser())->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_user_can_get_their_own_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $user = $this->utility->createUser();
        $user->name = 'newName';
        $user2 = $this->utility->createUser();
        $this->assertDatabaseCount('users', 2);

        $resp = $this->actingAs($user)->getJson('api/user');

        $resp->assertOk();
        $this->assertEquals('newName', json_decode($resp->getContent())->name);
    }

    public function test_user_can_get_only_their_own_profile()
    {
        $user = User::factory()->create(['name' => 'name1', 'role_id' =>User::ROLE['operator']]);
        $user2 = User::factory()->create(['name' => 'name2', 'role_id' =>User::ROLE['operator']]);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['name' => 'name1']);
        $this->assertDatabaseHas('users', ['name' => 'name2']);

        $resp = $this->actingAs($user)->getJson('api/user');

        $resp->assertJson(['name'=>'name1']);
        $resp->assertJsonMissing(['name'=>'name2']);
    }

    public function test_user_can_edit_only_their_own_profile()
    {
        $user = User::factory()->create(['role_id'=>User::ROLE['operator']]);
        $user2 = User::factory()->create(['name'=>'user2','role_id'=>User::ROLE['operator']]);

        $resp = $this->actingAs($user)->putJson('api/user', $this->userAttributes);

        $resp->assertOk();
        $this->assertDatabaseHas('users', ['name'=>$this->userAttributes['name']]);
        $this->assertDatabaseMissing('users', ['name'=>$user->name]);

    }

    public function test_user_cant_modify_their_owno_id()
    {

    }

    public function test_user_cant_edit_others_users_profiles()
    {

    }

    public function test_each_user_can_delete_only_their_own_profile()
    {

    }

}
