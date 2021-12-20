<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveLeadRequest;
use App\Models\Area;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
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
        // IMP ! gate works ONLY for authenticated users (if NOT it return ALWAYS false!)
        if(auth()->check() && !Gate::allows('create-lead')) {
            abort(403);
        }

        $params = $request->all();
        $areas = explode(',', $request->get('area'));
        $params['form'] = 'default lead form';
        $params['municipality'] = trim($areas[0]);
        $params['province'] = trim($areas[1]);
        $params['region'] = trim($areas[2]);
        $params['price'] = Lead::PRICE['default'];
        $params['phone'] = $this->sanitizePhone($request->get('phone'));
        $params['email'] = trim($request->get('email'));

        // check phone (if already present --> update lead)
        // check mail (if already present --> update lead)
        $this->leads = DB::table('leads')->get(['phone', 'email']); //
        // dd($this->leads);
        if($this->alreadyPresentInLeadsTable($params['phone'], 'phone') && $this->overThreeSubmissionsToday()) {
            // update TODO
        }elseif ($this->alreadyPresentInLeadsTable($params['email'], 'email')) {
            // update TODO
        }else {
            $lead = Lead::create($params);
            return response()->json(['lead' => $lead], 201);
        }
    }

    private function sanitizePhone(string $phone) : string
    {
        return str_replace(['.', '-', '/', ' '], '', $phone);
    }

    private function alreadyPresentInLeadsTable(string $string, string $column) : bool
    {
        $array = $this->leads->pluck($column)->toArray();
        return in_array($string, $array);
    }

    private function overThreeSubmissionsToday() : bool
    {

        // return; TODO
    }

}
