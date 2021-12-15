<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveApplicationFormRequest;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function municipalities()
    {
        $municipalities = [];
        $areas = DB::table('areas')->get(['city','prov_abbr','region_name']);
        foreach ($areas as $area) {
            $municipalities[] = $area->citi.', '.$area->prov_abbr.', '.$area->region_name;
        }
        dd($municipalities);
        return response()->json(['municipalities' => $municipalities]);
    }
    public function store(saveApplicationFormRequest $request)
    {
        $lead = Lead::create($request->all());
        return response()->json(['lead' => $lead]);
    }
}
