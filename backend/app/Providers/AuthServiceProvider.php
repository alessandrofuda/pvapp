<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-user', function (User $user) {
            return $user->isAdmin() || (auth()->check() && $user->id === auth()->user()->id);
        });

        Gate::define('delete-user', function(User $user) {
            return $user->isAdmin() || (auth()->check() && $user->id === auth()->user()->id);
        });

        Gate::define('create-lead', function(User $user) {
            if($user->isAdmin()) {
                return true;
            }

            if(auth()->check() && auth()->user()->role_id === User::ROLE['operator']) {
                return false;
            }
        });

//        Gate::define('update-lead', function(User $user) {
//
//        });

//        Gate::define('delete-lead', function(User $user) {
//
//        });
    }
}
