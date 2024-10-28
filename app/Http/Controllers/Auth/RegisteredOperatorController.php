<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveOperatorRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredOperatorController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $regions = DB::table('areas')
                ->select('region_id AS id', 'region_name AS name')
                ->groupBy('region_id', 'region_name')
                ->get()
                ->toArray();

        $regions = array_map(function($item) {
            $item->type = 'regione';
            return $item;
        }, $regions);

        $provinces = DB::table('areas')
                ->select('province_id AS id', 'province_name AS name')
                ->groupBy('province_id', 'province_name')
                ->where('province_id', '<>', 999)  // estero
                ->where('province_id', '<>', 998) // tutta italia
                ->get()
                ->toArray();
        $provinces = array_map(function($item){
            $item->type = 'provincia';
            return $item;
        }, $provinces);

        $areas = array_merge($regions, $provinces);

        // alhabetical order by name
        usort($areas, fn($a, $b) => strcmp($a->name, $b->name));

        return Inertia::render('Auth/RegisterOperator', ['areas_opts' => $areas]);
    }

    /**
     * Display the create operator view for admin.
     */
    public function createByAdmin(): Response
    {
        $regions = DB::table('areas')
                ->select('region_id AS id', 'region_name AS name')
                ->groupBy('region_id', 'region_name')
                ->get()
                ->toArray();

        $regions = array_map(function($item) {
            $item->type = 'regione';
            return $item;
        }, $regions);

        $provinces = DB::table('areas')
                ->select('province_id AS id', 'province_name AS name')
                ->groupBy('province_id', 'province_name')
                ->where('province_id', '<>', 999)  // estero
                ->where('province_id', '<>', 998) // tutta italia
                ->get()
                ->toArray();
        $provinces = array_map(function($item){
            $item->type = 'provincia';
            return $item;
        }, $provinces);

        $areas = array_merge($regions, $provinces);

        // alhabetical order by name
        usort($areas, fn($a, $b) => strcmp($a->name, $b->name));

        return Inertia::render('Auth/CreateOperator', ['areas_opts' => $areas]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function SaveOperator(SaveOperatorRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('operator');
        $user->operator()->create(['phone' => $request->phone]);
        $this->assignOperatorAreas($request->areas, $user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Handle the storage of an operator created by admin.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function storeByAdmin(SaveOperatorRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('operator');
        $user->operator()->create(['phone' => $request->phone]);
        $this->assignOperatorAreas($request->areas, $user);

        event(new Registered($user));

        return redirect(route('operators', absolute: false));
    }

    private function getIdsByAreaType(array $areas, string $type) : array
    {
        $ids = array_map(function($area) use ($type) {
            return ($area['type'] === $type) ? $area['id'] : null;
        }, $areas);

        return array_values(array_filter($ids)); // remove nulls and reindex
    }

    /**
     * @throws Exception
     */
    private function assignOperatorAreas(array $areas, User $user) : void
    {
        try{
            $regions_ids = $this->getIdsByAreaType($areas, 'regione');
            $provinces_ids = $this->getIdsByAreaType($areas, 'provincia');
            $user->operator->regions()->sync($regions_ids);
            $user->operator->provinces()->sync($provinces_ids);

        }catch(Exception $e) {
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }
}
