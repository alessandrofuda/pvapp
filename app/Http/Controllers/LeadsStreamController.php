<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use function PHPUnit\Framework\exactly;

class LeadsStreamController extends Controller
{
    /**
     * @throws Exception
     */
    public function leadsStream() : Response
    {
        try{
            $leads = Lead::with('area')
                ->where('status', 'approved')
                ->orderByDesc('id')
                ->limit(500)
                ->paginate(25);

        }catch (Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('LeadsStream', ['leads' => $leads]);
    }
}
