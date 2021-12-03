<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// fortify auth routes --> see 'php artisan route:list'

Route::get('/', function () {
//    dump('CSRF_TOKEN: ' . csrf_token());
//    dump(config('cors.allowed_origins'));
    return view('welcome'); //
});

Route::get('/home', function() {
   return 'HOME PAGE';
});
