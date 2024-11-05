<?php

namespace App\Domain;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Leads
{

    /**
     * @throws Exception
     */
    public function getTownsOpts() : array
    {
        try{
            return DB::table('areas')->select('id', 'region_name AS region', 'province_code AS prov', 'town')->get()->toArray();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }
}
