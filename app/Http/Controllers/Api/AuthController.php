<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8' ]
        ]);
        $user = User::create($validatedData);
        $token = $user->createToken('auth')->accessToken();

        return response()
            ->json(['user' => $user,
                    'token' => $token], 201);
    }
}
