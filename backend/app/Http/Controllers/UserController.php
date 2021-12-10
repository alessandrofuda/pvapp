<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function update(SaveUserRequest $request)
    {
        if(!Gate::allows('update-user', $request->user())) {
            abort(403);
        }

        $user = User::findOrFail($request->user()->id)->update($request->all());
        return response()->json(['updated' => $user]);
    }

    public function destroy(Request $request)
    {
        if(!Gate::allows('delete-user', $request->user())) {
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
