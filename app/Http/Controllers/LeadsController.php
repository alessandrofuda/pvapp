<?php

namespace App\Http\Controllers;

use App\Domain\Helpers;
use App\Domain\Leads;
use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LeadsController extends Controller
{

    public Request $request;
    public ?string $search;
    private Leads $leads;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->search = $request->get('search') ?? null;
        $this->leads = new Leads();
    }


    /**
     * @throws Exception
     */
    public function leads()
    {
        try{
            $leads = DB::table('leads')
                ->leftJoin('areas', 'leads.area_id', '=', 'areas.id')
                ->select(
                    'leads.id AS id',
                    'name',
                    'lastname',
                    'email',
                    'phone',
                    'region_name',
                    'province_name',
                    'town',
                    'description'
                )
                ->orderByDesc('leads.id')
                // search text
                ->when($this->search, function($query) {
                    $query->where(function($query) {

                        $columns = ['leads.name', 'leads.lastname', 'leads.email', 'leads.phone', 'region_name', 'province_name', 'town', 'description'];
                        foreach ($columns as $column) {
                            $query->orWhere($column, 'LIKE', '%'.$this->search.'%');
                        }

                    });
                })
                ->paginate(25);

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('Leads/Index', ['leads' => $leads]);
    }


    /**
     * Display the creation / edit Lead view for admin.
     * @throws Exception
     */
    public function lead(Lead $lead = null): Response
    {
        // dump($lead->with('area')->first());
        if ($lead) {
            $lead = Lead::with('area')->find($lead->id);
            //            $lead = DB::table('leads')
            //                ->where('leads.id', $lead->id)
            //                ->leftJoin('areas', 'leads.area_id', '=', 'areas.id')
            //                ->select(
            //                    'leads.id AS id',
            //                    'name',
            //                    'lastname',
            //                    'email',
            //                    'phone',
            //                    'areas.id AS area_id',
            //                    'areas.town AS town',
            //                    'areas.province_code AS prov_code',
            //                    'areas.province_name AS province',
            //                    'areas.region_name AS region',
            //                    'description'
            //                )
            //                ->first();
            //
            //            dd($lead);
        }
        $towns_opts = (new Leads)->getTownsOpts();

        return Inertia::render('Leads/EditOrCreate', ['lead' => $lead, 'towns_opts' => $towns_opts]);
    }

    /**
     * @throws Exception
     */
    public function saveLead(SaveLeadRequest $request, Lead $lead = null) : RedirectResponse
    {
        try{
            $lead_attr = [
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'area_id' => $request->town['id'],
                'description' => $request->description
            ];

            if($lead) {
                $lead->update($lead_attr);

            }else{
                Lead::create($lead_attr);
            }

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }

        return redirect(route('leads', absolute: false));
    }
}
