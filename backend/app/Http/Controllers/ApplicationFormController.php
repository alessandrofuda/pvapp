<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveApplicationFormRequest;
use App\Models\Lead;
use Illuminate\Http\Request;

class ApplicationFormController extends Controller
{
    public function store(saveApplicationFormRequest $request)
    {
        $application = Lead::create($request->all());
        return response()->json(['application' => $application]);
    }
}
