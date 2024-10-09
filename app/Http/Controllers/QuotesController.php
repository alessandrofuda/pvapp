<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class QuotesController extends Controller
{
    public function quoteForm() : Response
    {
        $towns_opts = DB::table('areas')->select('id', 'region_name AS region', 'province_code AS prov', 'town')->get()->toArray();
        return Inertia::render('QuotesForm', ['towns_opts' => $towns_opts]);
    }

    /**
     * @throws Exception
     */
    public function saveLead(SaveLeadRequest $request)
    {
        try{
            $fields = $request->except('town');
            $fields['area_id'] = $request->town['id'];

            Lead::create($fields);

        }catch(Exception $e) {
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }

        return redirect(route('quotes_form'));
    }
}
