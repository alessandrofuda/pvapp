<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class saveLeadRequest extends FormRequest
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
            'name' => 'required|string',
            'lastname' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'town' => 'required|array',
            'description' => 'nullable|string',
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'Inserisci un nome di riferimento',
            'email.required' => 'Inserisci una mail valida',
            'email.email' => 'Indirizzo e-mail non valido',
            'phone.required' => 'Inserisci il numero di telefono',
            'town.required' => 'Seleziona il Comune di Installazione'
        ];
    }
}
