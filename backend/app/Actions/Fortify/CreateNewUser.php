<?php

namespace App\Actions\Fortify;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public User $user;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $rules = (new SaveUserRequest)->rules();
        Validator::make($input, $rules)->validate();

        DB::transaction(function() use ($input) {
            $this->user = User::create([
                'name' => $input['first_name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role_id' => User::ROLE['operator'],
            ]);

            UserDetail::query()->create([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'user_id' => $this->user->id,
                'phone' => $input['phone'],
                'areas' => $input['areas']
            ]);
        });

        return $this->user;
    }
}
