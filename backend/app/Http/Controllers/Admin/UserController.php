<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    public function index() : JsonResponse
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function store(SaveUserRequest $request) : JsonResponse
    {
        $user = (new CreateNewUser())->create($request->all());
        return response()->json(['user' => $user], 201);
    }

    public function show(int $id) : JsonResponse
    {
        $user = User::find($id);
        return response()->json(['user' => $user]);
    }

    public function update(SaveUserRequest $request, int $id) : JsonResponse
    {
        $user = User::findOrFail($id)->update($request->all());
        return response()->json(['updated' => $user]);
    }

    public function destroy(int $id) : JsonResponse
    {
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
