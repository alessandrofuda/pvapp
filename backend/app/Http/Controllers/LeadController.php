<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LeadController extends Controller
{
    public function index() : JsonResponse
    {
        $leads = Lead::where('approved', true)->get();
        return response()->json(['leads' => $leads]);
    }

    public function municipalities() : JsonResponse
    {
        $municipalities = [];
        $areas = DB::table('areas')->get(['city','prov_abbr','region_name']);

        foreach ($areas as $area) {
            $municipalities[] = $area->city.', '.$area->prov_abbr.', '.$area->region_name;
        }

        return response()->json(['municipalities' => $municipalities]);
    }

    public function store(saveLeadRequest $request) : JsonResponse
    {
//        if(auth()->check() && $request->user()->role_id === User::ROLE['operator']) {
//            // abort(403);
//            return response()->json(['status' => 'Forbidden'], 403);
//        }

        if(! Gate::allows('create-lead')) {
            // abort(403);
            return response()->json(['status' => 'Forbidden'], 403);
        }

        $lead = Lead::create($request->all());
        return response()->json(['lead' => $lead], 201);
    }
}
