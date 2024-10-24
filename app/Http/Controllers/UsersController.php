<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function users(): Response
    {
        $users = User::paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }
}
