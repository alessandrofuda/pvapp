<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class QuotesController extends Controller
{
    public function quoteForm()
    {
        $towns_opts = [];
        return Inertia::render('QuotesForm', ['towns_opts' => $towns_opts]);
    }
}
