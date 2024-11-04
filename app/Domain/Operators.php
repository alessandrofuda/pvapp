<?php

namespace App\Domain;

use App\Models\Operator;
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
    public function assignOrSyncOperatorAreas(array $areas, Operator $operator) : void
    {
        try{
            $regions_ids = $this->getIdsByAreaType($areas, 'regione');
            $provinces_ids = $this->getIdsByAreaType($areas, 'provincia');
            $operator->regions()->sync($regions_ids);
            $operator->provinces()->sync($provinces_ids);

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

    /**
     * @throws Exception
     */
    public function groupingOperatorData(array $ungroupedData) : array
    {
        try{
            /*  AI generated, but I don't like it!
          $groupedData = array_reduce($ungroupedData, function ($result, $item) {

           // Initialize the grouped item if not set
           if (!isset($result[0])) {
               $result[0] = [
                   'id' => $item->id,
                   'user_id' => $item->user_id,
                   'name' => $item->name,
                   'email' => $item->email,
                   'email_verified_at' => $item->email_verified_at,
                   'phone' => $item->phone,
                   'regions' => [],
                   'provinces' => [],
               ];
           }

           // Add region if it exists
           if ($item->region) {
               $result[0]['regions'][] = [
                   'id' => $item->region['id'],
                   'name' => $item->region['name'],
                   'type' => 'regione'
               ];
           }

           // Add province if it exists
           if ($item->province) {
               $result[0]['provinces'][] = [
                   'id' => $item->province['id'],
                   'name' => $item->province['name'],
                   'type' => 'provincia'
               ];
           }

           return $result;
       }, []);*/

            $result = [];
            foreach ($ungroupedData as $ungroupedItem) {
                $result['id'] = $ungroupedItem->id;
                $result['user_id'] = $ungroupedItem->user_id;
                $result['name'] = $ungroupedItem->name;
                $result['email'] = $ungroupedItem->email;
                $result['email_verified_at'] = $ungroupedItem->email_verified_at;
                $result['phone'] = $ungroupedItem->phone;
                // $result['regions'] = null; NOO!!
                // $result['provinces'] = null; NO!!

                if ($ungroupedItem->region) {
                    $regions = $ungroupedItem->region;
                    $regions['type'] = 'regione';
                    $result['regions'][] = $regions;
                }

                if ($ungroupedItem->province) {
                    $provinces = $ungroupedItem->province;
                    $provinces['type'] = 'provincia';
                    $result['provinces'][] = $provinces;
                }
            }

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }

        return $result;
    }
}
