<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewOperator;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Http\Responses\LoginResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // override toResponse() method of RegisterResponse::class
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponseContract {
            public function toResponse($request)
            {
                return $request->wantsJson()
                    ? new JsonResponse(['success' => 'User registered correctly'], 201)
                    : abort(500, 'Register Request should have Accept:application/json header');
            }
        });

        // override toResponse() method of LoginResponse::class
        $this->app->instance(LoginResponse::class, new class implements LoginResponseContract {
            public function toResponse($request)
            {
                return $request->wantsJson()
                    ? response()->json(['success' => 'User Logged in correctly','two_factor' => false])
                    : abort(500, 'Login Request should have Accept:application/json header');
            }
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewOperator::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
