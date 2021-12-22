<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'between:7,30', new PhoneNumber ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user ?? ($this->user()->id ?? null)),
            ],
            'areas' => ['required', 'string'], // TODO
            'password' => array_merge(['sometimes'], $this->passwordRules())
        ];
    }
}
