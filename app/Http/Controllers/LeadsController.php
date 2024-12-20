<?php

namespace App\Http\Controllers;

use App\Domain\Helpers;
use App\Domain\Leads;
use App\Enums\LeadStatus;
use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use App\Models\Operator;
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
                    'description',
                    'price',
                    'status',
                    'leads.created_at AS date'
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
        if ($lead) {
            $lead = Lead::with('area')->find($lead->id);
        }
        $towns_opts = (new Leads)->getTownsOpts();
        $leads_status_opts = LeadStatus::options_for_select();

        return Inertia::render('Leads/EditOrCreate', ['lead' => $lead, 'towns_opts' => $towns_opts, 'leads_status_opts' => $leads_status_opts]);
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
                'description' => $request->description,
                'price' => $request->price
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

    /**
     * @throws Exception
     */
    public function changeLeadStatus(Request $request) : void
    {
        try{
            // dd($request->all());
            Lead::find($request->lead_id)->update(['status' => $request->value]);

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }

    /**
     * @throws Exception
     */
    public function deleteLead(Lead $lead) : RedirectResponse
    {
        try{
            $lead->delete();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }

        return redirect(route('leads', absolute: false));
    }

}
