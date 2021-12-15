<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/test', function() {
//    return 'test api: cors OK!';
//});

// guest routes
Route::get('municipalities', [LeadController::class, 'municipalities']);
Route::post('lead', [LeadController::class, 'store']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('user', [UserController::class, 'user']);
    Route::put('user', [UserController::class, 'update']);
    Route::delete('user', [UserController::class, 'destroy']);

    // admin
    Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::apiResource('users', AdminUserController::class);
        // Route::apiResource('leads', AdminLeadsController::class);
    });
});
