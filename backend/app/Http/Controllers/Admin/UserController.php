<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewOperator;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveOperatorRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;


class UserController extends Controller
{
    public function index() : JsonResponse
    {
        if(!Gate::any(['read-operators', 'read-admins'])) {
            abort(403);
        }

        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function store(SaveOperatorRequest $request) : JsonResponse
    {
        if(!Gate::any(['create-operator', 'create-admin'])) {
            abort(403);
        }

        $user = (new CreateNewOperator())->create($request->all());
        return response()->json(['user' => $user], 201);
    }

    public function show(int $id) : JsonResponse
    {
        if(!Gate::any(['read-operator', 'read-admin'])) {
            abort(403);
        }

        $user = User::find($id);
        return response()->json(['user' => $user]);
    }

    public function update(SaveOperatorRequest $request, int $id) : JsonResponse
    {
        if(!Gate::any(['update-operator', 'update-admin'])) {
            abort(403);
        }

        $user = User::findOrFail($id)->update($request->all());
        return response()->json(['updated' => $user]);
    }

    public function destroy(int $id) : JsonResponse
    {
        if(!Gate::any(['delete-operator', 'delete-admin'])) {
            abort(403);
        }

        try {
            $deleted = User::findOrFail($id)->delete();
            $status = 200;
        }catch (Exception $e) {
            $deleted = 'Exception: '.$e->getMessage();
            $status = 500;
        }

        return response()->json(['deleted' => $deleted], $status);
    }
}
