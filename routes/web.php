<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\OperatorsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\RegisteredOperatorController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomepageController::class, 'homepage']);

Route::get('quotes-form', [QuotesController::class, 'quoteForm'])->name('quotes_form');
Route::post('save-lead', [QuotesController::class, 'saveLead'])->name('save_lead');


Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // operators
    Route::get('/operators', [OperatorsController::class, 'operators'])->name('operators');
    Route::get('/operators/create', [RegisteredOperatorController::class, 'createByAdmin'])->name('operator_create_by_admin');
    Route::post('/operators/store', [RegisteredOperatorController::class, 'storeByAdmin'])->name('operator_store_by_admin');
    // leads
    Route::get('/leads', [LeadsController::class, 'leads'])->name('leads');
    // users
    Route::get('/users', [UsersController::class, 'users'])->name('users');
    // transactions
    Route::get('/transactions', [TransactionsController::class, 'transactions'])->name('transactions');


    // user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
