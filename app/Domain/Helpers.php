<?php

namespace App\Domain;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Helpers
{
    public function convertDotNotationsFieldsToNestedArrays(Collection $collection) : Collection
    {
        $undotted_array = [];
        foreach ($collection as $item) {
            $undotted_array[] = (object) (Arr::undot((array) $item)); // imp: re-cast item to object for flow-compatibility
        }

        $undotted_array = $this->convertNestedArraysToNullIfChildrenAreNulls($undotted_array);

        return collect($undotted_array);
    }

    private function convertNestedArraysToNullIfChildrenAreNulls(array $array) : ?object
    {
        return (object) array_map(function($item) {
            return (object) array_map(function($field) {
                if(is_array($field) && $this->isAssocArray($field) && $this->allItemsAreNull($field)) {
                    $field = null;
                }
                return $field;
            }, (array) $item);
        }, $array) ;
    }
    private function isAssocArray(array $arr) : bool
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function allItemsAreNull(array $arr) : bool
    {
        return count(array_filter(array_values($arr))) === 0;
    }
}
