<?php

namespace App\Http\Requests;

use App\Models\Operator;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255'

            ],
            'phone' => [
                'required',
                'min_digits:7',
                'max_digits:15'
            ],
            'areas' => 'required|array',
            'password' => [
                'sometimes', // validate Only if present...
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],
        ];

        if($this->id) {
            $rules['email'][] = Rule::unique('users')->ignore(Operator::with('user')->find($this->id)->user->id);
            $rules['phone'][] = Rule::unique('operators')->ignore($this->id);

            if(auth()->user()->hasRole('admin')) {
                unset($rules['password']);
            }

        } else {
            $rules['email'][] = Rule::unique('users');
            $rules['phone'][] = Rule::unique('operators');
        }

        return $rules;
    }

    public function messages() : array
    {
        return [
            'name.required' => 'Inserisci un Nome di riferimento',
            'email.required' => 'Inserisci la tua mail',
            'email.email' => 'E-mail non valida',
            'email.unique' => 'La mail inserita è già presente nei nostri sistemi',
            'phone.required' => 'Inserisci il numero di telefono',
            'phone.min_digits' => 'Il numero di telefono non valido',
            'phone.max_digits' => 'Il numero di telefono non valido',
            'phone.unique' => 'Il telefono inserito è già registrato nei nostri sistemi',
            'areas.required' => 'Cerca e seleziona una o più aree geografiche',
            'password.required' => 'Inserisci una password',
            'password.confirmed' => 'Le due passwords digitate non coincidono'
        ];
    }
}
