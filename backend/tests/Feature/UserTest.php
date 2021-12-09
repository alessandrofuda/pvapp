<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected array $userAttributes;
    private TestsUtility $utility;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->userAttributes = $this->utility->userAttributes();
    }

    public function test_admin_can_get_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->utility->createAdmin())->getJson('/api/admin/users');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertCount(3+1, $content->users);
    }

    public function test_user_cant_get_users()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->utility->createUser())->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_user_profile()
    {
        $this->assertDatabaseCount('users', 0);

        $response = $this->actingAs($this->utility->createAdmin())->postJson('api/admin/users', $this->userAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1+1);

    }

    public function test_any_guest_user_can_create_new_profile()
    {
        $this->assertDatabaseCount('users', 0);

        $response = $this->postJson('register', $this->userAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1);

    }


    public function test_each_user_can_shows_only_their_own_profile()
    {

    }

    public function test_admin_can_shows_all_singular_users_profiles()
    {

    }

    public function test_only_admin_can_edit_all_users_profiles()
    {

    }

    public function test_each_user_can_edit_only_their_own_profile()
    {

    }

    public function test_user_cant_edit_other_users_profiles()
    {

    }

    public function test_each_user_can_delete_only_their_own_profile()
    {

    }

    public function test_only_admin_can_delete_users_profiles()
    {

    }
}
