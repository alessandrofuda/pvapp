<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDetail;
use Database\Factories\LeadFactory;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_create_user_via_factory_user_details_have_been_created()
    {
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_details', 0);

        User::factory()
            ->count(5)
            ->hasDetail(1) // magic method!
            // ->has(UserDetail::factory()->count(1)) // first way to do same thing as above
            ->create();

        $this->assertDatabaseCount('users', 5);
        $this->assertDatabaseCount('user_details', 5);
    }

    public function test_UserFactory_is_aligned_with_real_db()
    {
        $db_columns = Schema::getColumnListing('users');
        $db_columns_filtered = array_filter($db_columns, function($item) {
            return ($item != 'id' &&
                $item != 'two_factor_secret' &&
                $item != 'two_factor_recovery_codes' &&
                $item != 'created_at' &&
                $item != 'updated_at');
        });
        $db_columns_filtered = array_values($db_columns_filtered);
        $factory_attributes = array_keys((new UserFactory)->definition());

        $this->assertEqualsCanonicalizing($factory_attributes, $db_columns_filtered);
    }

    public function test_userDetailFactory_is_aligned_with_real_db()
    {
        $db_columns = Schema::getColumnListing('user_details');
        $db_columns_filtered = array_filter($db_columns, function($item) {
            return ($item != 'id' && $item != 'created_at' && $item != 'updated_at');
        });
        $db_columns_filtered = array_values($db_columns_filtered);
        $factory_attributes = array_keys((new UserDetailFactory)->definition());

        $this->assertEqualsCanonicalizing($factory_attributes, $db_columns_filtered);
    }

    public function test_LeadFactory_is_aligned_with_real_db()
    {
        $db_columns = Schema::getColumnListing('leads');
        $db_columns_filtered = array_filter($db_columns, function($item) {
            return ($item != 'id' && $item != 'created_at' && $item != 'updated_at');
        });
        $db_columns_filtered = array_values($db_columns_filtered);
        $factory_attributes = array_keys((new LeadFactory)->definition());

        $this->assertEqualsCanonicalizing($factory_attributes, $db_columns_filtered);
    }
}
