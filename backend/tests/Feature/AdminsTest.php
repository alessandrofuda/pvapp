<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class AdminsTest extends TestCase
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
        $this->assertDatabaseCount('users', 0);
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->utility->createAdmin())->getJson('/api/admin/users');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertCount(3+1, $content->users);
    }

    public function test_admin_can_create_new_user_profile()
    {
        $this->assertDatabaseCount('users', 0);
        $response = $this->actingAs($this->utility->createAdmin())->postJson('api/admin/users', $this->userAttributes);

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1+1);

    }

    public function test_admin_can_shows_each_singular_users_profiles()
    {
        $this->assertDatabaseCount('users', 0);
        $user = User::factory()->create(['id'=>5]);

        $resp = $this->actingAs($this->utility->createAdmin())->getJson('api/admin/users/'.$user['id']);

        $resp->assertOk();
        $this->assertDatabaseCount('users', 1+1);
    }

    public function test_only_admin_can_edit_all_users_profiles()
    {
        $this->assertDatabaseCount('users', 0);
        $user = User::factory()->create(['id'=>6, 'name'=>'name']);
        $this->assertDatabaseHas('users', ['name'=> 'name']);

        $userAttributes = $this->userAttributes;
        $userAttributes['name'] = 'newName';

        $risp = $this->actingAs($this->utility->createAdmin())->putJson('api/admin/users/'.$user['id'], $userAttributes);

        $risp->assertOk();
        $this->assertDatabaseHas('users', ['name'=>'newName']);

        $risp2 = $this->actingAs($this->utility->createUser())->putJson('api/admin/users/'.$user['id'], $userAttributes);
        $risp2->assertForbidden();
    }

    public function test_only_admin_can_delete_users_profiles()
    {
        $user = User::factory()->create(['id'=>8]);
        $risp = $this->actingAs($this->utility->createAdmin())->deleteJson('api/admin/users/'.$user['id']); // this create a record in users table

        $risp->assertOk();
        $this->assertDatabaseCount('users', 1);

        $risp2 = $this->actingAs($this->utility->createUser())->deleteJson('api/admin/users/'.$user['id']); // this create a record in users table
        $risp2->assertForbidden();
        $this->assertDatabaseCount('users', 2);
    }
}
