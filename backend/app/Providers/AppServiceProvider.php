<?php

namespace App\Providers;

use App\Custom\EnvironmentPathCustomization;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // alter .env path one level UP !!!
        // see: /bootstrap/app.php  --> $app->useEnvironmentPath(base_path('..'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
