<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class saveLeadRequest extends FormRequest
{
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
            'services_ids' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],  // Rule::unique(User::class)->ignore($this->user ?? ($this->user()->id ?? null)),
            'phone' => ['required', 'string', 'between:7,30', new PhoneNumber ],
            'area' => ['required', 'string', 'max:255'], // todo add regex rule: 'comune, prov, region'
            'description' => ['string', 'max:5000']
        ];
    }
}
