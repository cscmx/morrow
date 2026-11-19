<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8' ]
        ]);
        $user = User::create($validatedData);
        $user->assignRole('user');
        
        return response()->json(['user' => $user], 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }
}
