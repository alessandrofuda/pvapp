<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
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
Route::get('leads', [LeadController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    // operators
    Route::group(['middleware' => 'isOperator', 'as' => 'operator.'], function() {
         Route::get('user', [OperatorController::class, 'show']);
         Route::put('user', [OperatorController::class, 'update']);
         Route::delete('user', [OperatorController::class, 'destroy']);
    });
    // admins
    Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::apiResource('users', AdminUserController::class);
        Route::apiResource('leads', AdminLeadController::class);
    });
});
