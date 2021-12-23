<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LeadAreaValidation implements Rule
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
    public function passes($attribute, $value) : bool
    {
        if($this->isTwoCommaSpacesSeparatedString($this->sanitizeArea($value))) { // verify the format: "Municipality, MI, Region"

            $areas = $this->sanitizeAreaAndConvertToArray($value);
            $city = $areas[0];
            $province = $areas[1];
            $region = $areas[2];

            $db_areas = DB::table('areas')->get(['city', 'prov_abbr', 'region_name'])->toArray();
            $db_areas_chunks = array_chunk($db_areas, 1000);

            foreach ($db_areas_chunks as $db_areas) {
                foreach ($db_areas as $db) {
                    if( trim($db->city) === $city &&
                        trim($db->prov_abbr) === $province &&
                        trim($db->region_name) === $region) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Campo "Luogo di Installazione" non valido.';
    }

    public function sanitizeAreaAndConvertToArray(string $area) : array
    {
        $areas = explode(',', $this->sanitizeArea($area));
        $areas = array_values(array_filter($areas)); // remove nulls & reindex (IMP!)

        return array_map(function($item) {
            return trim($item);
        }, $areas);
    }

    private function sanitizeArea(string $area) : string
    {
        return trim($area, ',.;:"');
    }

    private function isTwoCommaSpacesSeparatedString(string $string) : bool
    {
        return preg_match('/^[a-z .\-\p{L}\']+, [a-z]{2}, [a-z .\-\p{L}\']+$/i', $string);
    }
}
