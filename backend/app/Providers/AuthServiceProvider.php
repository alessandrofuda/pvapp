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
        'App\Models\Lead' => 'App\Policies\LeadPolicy',
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

    }
}
