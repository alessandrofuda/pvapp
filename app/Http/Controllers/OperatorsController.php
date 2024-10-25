<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $operators = DB::table('users')
                // Join with the model_has_roles table to filter by the 'operator' role (role_id = 3)
                ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', 3)  // Role 'operator' has role_id = 3

                // Join with the operators table to get operator-specific data (1-to-1 relation with users)
                ->leftJoin('operators', 'users.id', '=', 'operators.user_id')

                // Join with the operator_areas pivot table to get the areas the operator is linked to
                ->leftJoin('operator_areas', 'operators.id', '=', 'operator_areas.operator_id')

                // Join with the regions/provinces tables to get region and province names (table populated via areasSeeder)
                ->leftJoin('regions', 'operator_areas.region_id', '=', 'regions.id')
                ->leftJoin('provinces', 'operator_areas.province_id', '=', 'provinces.id')

                // Select the desired fields
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'operators.phone',
                    DB::raw("GROUP_CONCAT(regions.name SEPARATOR ', ') AS region_names"),
                    DB::raw("GROUP_CONCAT(provinces.name,' (provincia)' SEPARATOR ', ') AS province_names")  // 'regions.name AS region_name', 'provinces.name AS province_name')
                )
                ->groupBy('id', 'name', 'email', 'phone')
                ->orderBy('users.id')
                ->paginate(25);
                // ->get();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('Operators/Index', ['operators' => $operators, 'filters' => '..todo..']);
    }
}
