<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(\+)?([0-9.\-\/ ])+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The :attribute must be a Phone Number including only numbers, spaces or \'+\', \'/\', \'.\' symbols';
        return 'Il campo Telefono deve includere solo: numeri, spazi o questi caratteri: \'+\', \'/\', \'.\'.';
    }
}
