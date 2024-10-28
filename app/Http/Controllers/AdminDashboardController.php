<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function adminDashboard() : Response
    {
        return Inertia::render('AdminDashboard');
    }
}
