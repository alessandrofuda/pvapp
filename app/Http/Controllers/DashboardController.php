<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function dashboard() : Response
    {
        return Inertia::render('Dashboard');
    }
}
