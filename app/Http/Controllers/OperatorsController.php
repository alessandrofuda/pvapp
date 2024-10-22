<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OperatorsController extends Controller
{
    /**
     * @throws Exception
     */
    public function operators() : Response
    {
        try{
            $operators = User::role('operator')->with('operator')->get();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('Operators/Index', ['operators' => $operators, 'filters' => '..todo..']);
    }
}
