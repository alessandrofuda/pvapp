<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function update(SaveUserRequest $request) : JsonResponse
    {
        if(!Gate::allows('update-operator', $request->user())) {
            abort(403);
        }

        $user = User::findOrFail($request->user()->id)->update($request->except(['id', 'role_id'])); // $request->all()
        return response()->json(['updated' => $user]);
    }

    public function destroy(Request $request) : JsonResponse
    {
        if(!Gate::allows('delete-operator', $request->user())) {
            abort(403);
            // return response()->json(['status' => 'Forbidden'], 403);
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
