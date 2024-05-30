<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOperatorRequest;
use App\Models\User;
use App\Models\OperatorDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OperatorController extends Controller
{
    public function show(Request $request) : JsonResponse
    {
        if(!Gate::allows('read-operator')) {
            abort(403);
        }

        $user = User::with('detail')->whereId($request->user()->id)->orderByDesc('created_at')->first();
        return response()->json(['user' => $user]);
    }

    public function update(SaveOperatorRequest $request) : JsonResponse
    {
        if(!Gate::allows('update-operator')) {
            abort(403);
        }

        $userId = $request->user()->id;
        User::findOrFail($userId)->update($request->except(['id', 'role_id']));
        OperatorDetail::where('user_id', $userId)->update($request->except(['name', 'email', 'password', 'password_confirmation']));

        return response()->json(['updated' => true]);
    }

    public function destroy(Request $request) : JsonResponse
    {
        if(!Gate::allows('delete-operator')) {
            abort(403);
        }

        try {
            $deleted = User::findOrFail($request->user()->id)->delete();
            $status = 200;
        }catch (Exception $e) {
            $deleted = 'Exception: '.$e->getMessage();
            $status = 500;
        }

        return response()->json(['deleted' => $deleted], $status);
    }
}
