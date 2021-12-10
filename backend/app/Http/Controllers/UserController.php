<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function update(SaveUserRequest $request)
    {
        // $this->authorize(); TODO

        $user = User::findOrFail($request->user()->id)->update($request->all());
        return response()->json(['updated' => $user]);
    }
}
