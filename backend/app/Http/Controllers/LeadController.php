<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use App\Rules\LeadAreaValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;

class LeadController extends Controller
{
    public Collection $leads;

    public function index() : JsonResponse
    {
        $leads = Lead::where('approved', true)->get();
        $leads->makeHidden(['surname', 'email', 'phone']);

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
        // IMP ! gate works ONLY for authenticated users (if NOT it ALWAYS returns false!)
        if(auth()->check() && !Gate::allows('create-lead')) {
            abort(403);
        }

        $params = $request->all();
        $areas = (new LeadAreaValidation)->sanitizeAreaAndConvertToArray($request->get('area'));

        $params['form'] = 'default lead form';
        $params['municipality'] = $areas[0];
        $params['province'] = $areas[1];
        $params['region'] = $areas[2];
        $params['price'] = Lead::PRICE['default'];
        $params['phone'] = $this->sanitizePhone($request->get('phone'));
        $params['email'] = trim($request->get('email'));
        unset($params['area']);

        // check phone & mail. If already present --> update lead
        $this->leads = DB::table('leads')->get(['phone', 'email']);

        if( $this->alreadyPresentInLeadsTable($params['phone'], 'phone') ||
            $this->alreadyPresentInLeadsTable($params['email'], 'email')) {

            $status = ($lead = $this->updateIfNotManySubmissionsToday($request, $params)) ? 200 : 429;

        } else {
            $lead = Lead::create($params);
            $status = 201;
        }
        return response()->json(['lead' => $lead], $status ?? 500);
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

    private function updateIfNotManySubmissionsToday($request, $params) : bool
    {
        $maxAttempts = 2;
        $decaySeconds = 60*60*24;

        return RateLimiter::attempt(
            'lead-resubmitted-today.IP:'.$request->ip(),
            $maxAttempts,
            function() use ($request, $params) {
                return Lead::where('phone', $this->sanitizePhone($request->get('phone')))->orWhere('email', $request->get('email'))->update($params);
            },
            $decaySeconds
        );
    }

}
