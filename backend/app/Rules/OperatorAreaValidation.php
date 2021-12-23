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
    public function passes($attribute, $value) : bool
    {
        $request_areas = array_filter($this->requestAreas($value));
        $db_areas = $this->dbProvincesRegionsAreas();

        foreach ($request_areas as $request_area) {
            if(!in_array($request_area, $db_areas)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Campo "Area" non valido';
    }


    private function sanitizeArea(string $area) : string
    {
        return trim(str_replace(['(Provincia)', ','],'', $area));
    }

    private function requestAreas(mixed $value) : array
    {
        $req_areas = explode(',', trim($value, ',.;'));

        return array_map(function($item) {
                return $this->sanitizeArea($item);
            }, $req_areas);
    }

    private function dbProvincesRegionsAreas() : array
    {
        $db_areas = DB::table('areas')->select('prov_name', 'region_name')->groupBy('prov_name', 'region_name')->get()->toArray();
        $db_provinces = array_map(function($item) {
            return $item->prov_name;
        }, $db_areas);

        $db_regions = array_map(function($item) {
            return $item->region_name;
        }, $db_areas);

        return array_unique(array_merge($db_provinces, $db_regions));
    }
}
