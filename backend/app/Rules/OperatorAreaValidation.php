<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OperatorAreaValidation implements Rule
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
        $areas = explode(',', $value);
        $db_areas = DB::table('areas')->get(['prov_name', 'region_name'])->toArray(); // ->groupBy('prov_name')

        // dump($db_areas);  // TODO

        foreach ($areas as $area) {
            $area = $this->sanitizeArea($area);
        }
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Campo "Area" non valido';
    }

    private function sanitizeArea(string $area)
    {
        return trim(str_replace(['(Provincia)', ','],'', $area));
    }
}
