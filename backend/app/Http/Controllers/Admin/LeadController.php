<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\saveLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;


class LeadController extends Controller
{
    public function index() : JsonResponse
    {
        $leads = Lead::all();
        return response()->json(['leads' => $leads]);
    }

    public function store(saveLeadRequest $request) : JsonResponse
    {
        if(!Gate::allows('create-lead')) {
            abort(403);
        }

        $lead = Lead::create($request->all());
        return response()->json(['lead' => $lead], 201);
    }

    public function show(int $id) : JsonResponse
    {
        $lead = Lead::findOrFail($id);

        if(!Gate::allows('read-lead', $lead)) {
            abort(403);
        }

        return response()->json(['lead' => $lead]);
    }

    public function update(saveLeadRequest $request, int $id) : JsonResponse
    {
        if(!Gate::allows('update-lead')) {
            abort(403);
        }

        $lead = Lead::findOrFail($id)->update($request->all());
        return response()->json(['lead' => $lead]);
    }

    public function destroy(int $id) : JsonResponse
    {
        if(!Gate::allows('delete-lead')) {
            abort(403);
        }

        $deleted = Lead::destroy($id);
        return response()->json(['deleted' => $deleted]);
    }
}
