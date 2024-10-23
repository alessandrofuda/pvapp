<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OperatorsController extends Controller
{
    public Request $request;
    public ?string $search;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->search = $request->get('search') ?? null;
    }
    /**
     * @throws Exception
     */
    public function operators() : Response
    {
        try{
            $operators = User::role('operator')
                ->with('operator')
                ->when($this->search, function($query){
                    $query->where(function($query){
                        $columns = ['name', 'email'];
                        foreach ($columns as $column) {
                            $query->orWhere('users.'.$column, 'LIKE', '%'.$this->search.'%');
                        }
                        $query->orWhere('operators.phone', 'LIKE', '%'.$this->search.'%');
                    });
                })
                // ->get();
                ->paginate(10)
                ->withQueryString();

            dd($operators);

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('Operators/Index', ['operators' => $operators, 'filters' => '..todo..']);
    }
}
