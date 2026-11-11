<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



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
        $token = $user->createToken('auth')->accessToken;

        return response()
            ->json(['user' => $user,
                    'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
            ]
        );
        $user = User::where('email', $validatedData['email'])->first();

        if(! $user || ! Hash::check($validatedData['password'], $user->password))
            {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }
        $token = $user->createToken('auth')->accessToken;

        return response()->json(['user'=> $user, 'token' => $token], 200);
    }
    
    public function logout(Request $request)
    {
        $user = $request->user();
        
        $user->token()->revoke();
        
        return response()->json(['message' => 'Logged out'], 200);
    }
}
