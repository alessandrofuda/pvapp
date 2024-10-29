<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserDashboardController extends Controller
{
    public function userDashboard() : Response
    {
        return Inertia::render('UserDashboard');
    }
}
