<?php

namespace Tests\Feature\guestTests;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\TestsUtility;

class LoginRegisterUsersTest extends TestCase
{
    use RefreshDatabase;

    public TestsUtility $utility;
    public array $userRequestAttributes;

    protected function setUp() : void
    {
        parent::setUp();
        $this->utility = new TestsUtility();
        $this->userRequestAttributes = $this->utility->userRequestAttributes();
    }

    public function test_any_guest_user_can_create_new_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $response = $this->postJson('register', $this->userRequestAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1);

    }

    public function test_during_user_registration_user_details_have_been_created()
    {
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_details', 0);

        $resp = $this->postJson('register', $this->userRequestAttributes);

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
        User::factory()->create(['email' => $this->userRequestAttributes['email']]);

        $response = $this->postJson('register', $this->userRequestAttributes);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_guest_cant_get_users()
    {
        User::factory()->count(5)->create();
        $this->assertDatabaseCount('users', 5);

        $resp = $this->getJson('api/admin/users');

        $resp->assertUnauthorized();
    }

    public function test_guest_cant_get_user_profile()
    {
        User::factory()->count(5)->create();
        $this->assertDatabaseCount('users', 5);

        $resp = $this->getJson('api/user');

        $resp->assertUnauthorized();
    }

    public function test_guest_cant_post_user_profile_as_admin_profile()
    {
        $resp = $this->postJson('api/admin/users', $this->userRequestAttributes);

        $resp->assertUnauthorized();
    }

    public function test_guest_cant_delete_user_profile()
    {
        $resp = $this->deleteJson('api/user');

        $resp->assertUnauthorized();
    }

}
