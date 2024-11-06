<?php

namespace App\Http\Controllers;

use App\Domain\Helpers;
use App\Domain\Operators;
use App\Http\Requests\SaveOperatorRequest;
use App\Models\Operator;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OperatorsController extends Controller
{
    public Request $request;
    public ?string $search;
    private Operators $operators;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->search = $request->get('search') ?? null;
        $this->operators = new Operators();
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
                    'operators.id AS id',
                    'users.id AS user_id',
                    'users.name',
                    'users.email',
                    'operators.phone',
                    'users.email_verified_at',
                    DB::raw("GROUP_CONCAT(regions.name SEPARATOR ', ') AS region_names"),
                    DB::raw("GROUP_CONCAT(provinces.name,' (provincia)' SEPARATOR ', ') AS province_names")
                )
                ->groupBy('id', 'name', 'email', 'phone')
                ->orderBy('users.id')
                // search text
                ->when($this->search, function($query) {
                    $query->where(function($query) {

                        $columns = ['users.name', 'users.email', 'operators.phone', 'regions.name', 'provinces.name'];
                        foreach ($columns as $column) {
                            $query->orWhere($column, 'LIKE', '%'.$this->search.'%');
                        }

                    });
                })
                ->paginate(25);
                // ->get();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
        return Inertia::render('Operators/Index', ['operators' => $operators]);
    }

    /**
     * Display the creation / edit operator view for admin.
     * @throws Exception
     */
    public function operator(Operator $operator = null): Response
    {
        if ($operator) {
            $operator_collection = DB::table('operators')
                ->where('operators.id', $operator->id)
                ->leftJoin('users', 'users.id', '=', 'operators.user_id')
                ->leftJoin('operator_areas', 'operators.id', '=', 'operator_areas.operator_id')
                ->leftJoin('regions', 'operator_areas.region_id', '=', 'regions.id')
                ->leftJoin('provinces', 'operator_areas.province_id', '=', 'provinces.id')
                ->select('operators.id AS id',
                    'user_id',
                    'users.name AS name',
                    'email',
                    'email_verified_at',
                    'phone',
                    'regions.id AS region.id',
                    'regions.name AS region.name',
                    'province_id AS province.id',
                    'provinces.name AS province.name'
                )
                ->get();

            $ungroupedData = (new Helpers())->convertDotNotationsFieldsToNestedArrays($operator_collection)->toArray();
            $operator = $this->operators->groupingOperatorData($ungroupedData);
        }

        $areas = $this->operators->getAreasOpts();

        return Inertia::render('Operators/EditOrCreate', ['operator' => $operator, 'areas_opts' => $areas]);
    }

    /**
     * Handle the storage of an operator created by admin.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function saveOperator(SaveOperatorRequest $request, Operator $operator = null): RedirectResponse
    {
        $user_attr = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if($operator) {

            if(auth()->user()->hasRole('admin')){
                unset($user_attr['password']);  // password remains the same
            }

            $operator->user()->update($user_attr);
            $operator->update(['phone' => $request->phone]);
            $this->operators->assignOrSyncOperatorAreas($request->areas, $operator);

        }else{
            $user = User::create($user_attr);
            $user->assignRole('operator');
            $user->operator()->create(['phone' => $request->phone]);
            $this->operators->assignOrSyncOperatorAreas($request->areas, $user->operator);

            event(new Registered($user));
        }

        return redirect(route('operators', absolute: false));
    }

    /**
     * @throws Exception
     */
    public function deleteOperator(Operator $operator) : RedirectResponse
    {
        try{
            $operator->user->roles()->detach();
            $operator->user()->delete();

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }

        return redirect(route('operators', absolute: false));
    }
}
