<?php

namespace App\Domain;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Operators
{
    /**
     * @return array
     */
    public function getAreasOpts(): array
    {
        $regions = DB::table('areas')
            ->select('region_id AS id', 'region_name AS name')
            ->groupBy('region_id', 'region_name')
            ->get()
            ->toArray();

        $regions = array_map(function ($item) {
            $item->type = 'regione';
            return $item;
        }, $regions);

        $provinces = DB::table('areas')
            ->select('province_id AS id', 'province_name AS name')
            ->groupBy('province_id', 'province_name')
            ->where('province_id', '<>', 999)  // estero
            ->where('province_id', '<>', 998) // tutta italia
            ->get()
            ->toArray();
        $provinces = array_map(function ($item) {
            $item->type = 'provincia';
            return $item;
        }, $provinces);

        $areas = array_merge($regions, $provinces);

        // alhabetical order by name
        usort($areas, fn($a, $b) => strcmp($a->name, $b->name));
        return $areas;
    }

    /**
     * @throws Exception
     */
    public function assignOperatorAreas(array $areas, User $user) : void
    {
        try{
            $regions_ids = $this->getIdsByAreaType($areas, 'regione');
            $provinces_ids = $this->getIdsByAreaType($areas, 'provincia');
            $user->operator->regions()->sync($regions_ids);
            $user->operator->provinces()->sync($provinces_ids);

        }catch(Exception $e) {
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }

    private function getIdsByAreaType(array $areas, string $type) : array
    {
        $ids = array_map(function($area) use ($type) {
            return ($area['type'] === $type) ? $area['id'] : null;
        }, $areas);

        return array_values(array_filter($ids)); // remove nulls and reindex
    }

}
