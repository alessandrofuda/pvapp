<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'guard_name' => 'web', 'created_at' => now()],
            ['id' => 2, 'name' => 'user', 'guard_name' => 'web', 'created_at' => now()],
            ['id' => 3, 'name' => 'operator', 'guard_name' => 'web', 'created_at' => now()]
        ]);

        User::factory()->create(['name' => 'Test Admin', 'email' => 'admin@example.com'])->assignRole('admin');
        User::factory()->create(['name' => 'Test User', 'email' => 'user@example.com'])->assignRole('user');
        User::factory()->create(['name' => 'Test Operator', 'email' => 'operator@example.com'])->assignRole('operator');
    }
}
