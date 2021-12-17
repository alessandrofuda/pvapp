<?php

namespace App\Providers;

use App\Models\Lead;
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

        // Admins crud
        Gate::define('create-admin', function(User $user) {
            return $user->isAdmin();
        });
        Gate::define('read-admin', function(User $user) {
            return $user->isAdmin();
        });
        Gate::define('update-admin', function(User $user) {
            return $user->isAdmin();
        });
        Gate::define('delete-admin', function(User $user) {
            return $user->isAdmin() && User::where('role_id', User::ROLE['admin']->get() > 1);
        });

        // Operators (installer) crud
        Gate::define('create-operator', function(User $user) {
            return $user->isAdmin() || !auth()->check();
        });
        Gate::define('read-operator', function(User $user) {
            return $user->isAdmin() || (auth()->check() && $user->id === auth()->user()->id);
        });
        Gate::define('update-operator', function (User $user) {
            return $user->isAdmin() || (auth()->check() && $user->id === auth()->user()->id);
        });

        Gate::define('delete-operator', function(User $user) {
            return $user->isAdmin() || (auth()->check() && $user->id === auth()->user()->id);
        });

        // Leads crud
        Gate::define('create-lead', function(User $user) {  // IMP! this works ONLY for Authenticated users!
            return $user->isAdmin() || !$user->isOperator() || !auth()->check();
        });
        Gate::define('read-lead', function(User $user, Lead $lead) {
            return $user->isAdmin() || $user->hasPurchasedLead($lead);
        });
        Gate::define('update-lead', function(User $user) {
            return $user->isAdmin();
        });
        Gate::define('delete-lead', function(User $user) {
            return $user->isAdmin();
        });

    }
}
