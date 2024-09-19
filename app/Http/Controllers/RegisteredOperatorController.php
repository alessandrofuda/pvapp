<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOperatorRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function SaveOperator(SaveOperatorRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('operator');

        $user->operator()->create([
            'phone' => $request->phone,
            'areas' => $request->areas
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
