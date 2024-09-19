<?php

namespace App\Http\Requests;

use App\Models\Operator;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;


class SaveOperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'min_digits:7|max_digits:15|unique:'.Operator::class,
            'areas' => 'required|array',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
