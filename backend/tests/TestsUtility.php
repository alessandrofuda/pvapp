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

    public function createUser() : Authenticatable|Model
    {
        return User::factory()->create(['role_id' => User::ROLE['operator']]);
    }

    public function userAttributes() : array
    {
        return [
            'name' => 'Giovannino',
            'email'=>'giovannino@giovannino.com',
            'password'=>'password',
            'password_confirmation'=>'password'
        ];
    }
}
