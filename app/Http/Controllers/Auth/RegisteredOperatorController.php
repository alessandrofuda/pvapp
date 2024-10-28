<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Operators;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveOperatorRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredOperatorController extends Controller
{
    private Operators $operators;

    public function __construct()
    {
        $this->operators = new Operators();
    }
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $areas = $this->operators->getAreasOpts();

        return Inertia::render('Auth/RegisterOperator', ['areas_opts' => $areas]);
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
        $this->operators->assignOperatorAreas($request->areas, $user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('operator_dashboard', absolute: false));
    }
}
