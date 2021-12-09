<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
