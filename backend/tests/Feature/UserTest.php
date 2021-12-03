<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_get_all_users()
    {
        $admin = User::factory()->create(['role_id' => User::ROLE['admin']]);
        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->getJson('/api/admin/users');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertCount(3+1, $content->users);
    }

    public function test_user_cant_get_users()
    {
        $user = User::factory()->create(['role_id' => User::ROLE['operator']]);
        User::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_user_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $admin = User::factory()->create(['role_id'=>User::ROLE['admin']]);

        $response = $this->actingAs($admin)->postJson('api/admin/users', [
            'name' => 'Giovannino',
            'email'=>'giovannino@giovannino.com',
            'password'=>'password',
            'password_confirmation'=>'password'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1+1);

    }

    public function test_any_user_can_create_new_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $user = User::factory()->create(['role_id'=>User::ROLE['operator']]);

        $response = $this->actingAs($user)->postJson('register', [
            'name' => 'Giovannino',
            'email'=>'giovannino@giovannino.com',
            'password'=>'password',
            'password_confirmation'=>'password'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1+1);

    }
    // TODO: optimize remove duplicated code


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
