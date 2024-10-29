<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\OperatorDashboardController;
use App\Http\Controllers\OperatorsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomepageController::class, 'homepage']);

Route::get('quotes-form', [QuotesController::class, 'quoteForm'])->name('quotes_form');
Route::post('save-lead', [QuotesController::class, 'saveLead'])->name('save_lead');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::group(['middleware' => ['role:admin']], function () {
        // admin dashboard
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('admin_dashboard');
        // operators
        Route::get('/operators', [OperatorsController::class, 'operators'])->name('operators');
        Route::get('/operator/{operator?}', [OperatorsController::class, 'operator'])->name('operator')->where('operator', '[0-9]+');
        Route::post('/operator', [OperatorsController::class, 'saveOperator'])->name('save_operator');
        Route::put('/operator/edit-{operator}', [OperatorsController::class, 'saveOperator'])->name('edit_operator')->where('operator', '[0-9]+');

        // leads
        Route::get('/leads', [LeadsController::class, 'leads'])->name('leads');
        // users
        Route::get('/users', [UsersController::class, 'users'])->name('users');
        // transactions
        Route::get('/transactions', [TransactionsController::class, 'transactions'])->name('transactions');
    });

    Route::group(['middleware' => ['role:operator']], function () {
        // operator dashboard
        Route::get('/operator-dashboard', [OperatorDashboardController::class, 'operatorDashboard'])->name('operator_dashboard');
        // todo
    });

    Route::group(['middleware' => ['role:user']], function () {
        // user dashboard
        Route::get('/user-dashboard', [UserDashboardController::class, 'userDashboard'])->name('user_dashboard');
        // todo
    });


    // user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
