<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OperatorDashboardController extends Controller
{
    public function operatorDashboard() : Response
    {
        return Inertia::render('OperatorDashboard');
    }
}
