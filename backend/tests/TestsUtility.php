<?php

namespace Tests;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;


class TestsUtility
{
    public function createAdmin() : Authenticatable|Model
    {
        return User::factory()->create(['role_id' => User::ROLE['admin']]);
    }

    public function createOperator() : Authenticatable|Model
    {
        return User::factory()->create(['role_id' => User::ROLE['operator']]);
    }

    public function userRequestAttributes() : array
    {
        return [
            'firstname' => 'Giovannino',
            'lastname' => 'Rossi',
            'phone' => '+39 999.999999999',
            'email'=>'giovannino@giovannino.com',
            'password'=>'password',
            'password_confirmation' => 'password',
            'areas' => 'tests areas'
        ];
    }

    public function leadRequestAttributes() : array
    {
        return [
            'services_ids' => '1, 2, 3, 4',
            'name' => 'Pippo',
            'surname' => 'Rossi',
            'email' => 'pippo@rossi.com',
            'phone' => '+39 556 6598778',
            'area' => 'City Test, MI, Lombardia',
            'description' => 'Lorem Ipsum, Lorem Ipsum, Lorem Ipsum, Lorem Ipsum, ',
        ];
    }
}
