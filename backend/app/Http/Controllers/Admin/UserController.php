<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaveUserRequest $request)
    {
        $user = (new CreateNewUser())->create($request->all());
        return response()->json(['user' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json(['user' => $user]);  // in NOT-admin version --> TODO middleware: UserCanEditOnlyTheirOwnProfile
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SaveUserRequest $request, $id)
    {
        $user = User::findOrFail($id)->update($request->all());
        return response()->json(['updated' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
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
