<?php

namespace App\Http\Controllers;

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

class QuotesController extends Controller
{
    /**
     * @throws Exception
     */
    public function quoteForm() : Response
    {
        $towns_opts = (new Leads)->getTownsOpts();
        return Inertia::render('QuotesForm', ['towns_opts' => $towns_opts]);
    }

    /**
     * @throws Exception
     */
    public function saveQuotationRequest(SaveLeadRequest $request) : void
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
    }
}
