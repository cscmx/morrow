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

    public function store()
    {
        Gate::authorize('create', User::class);
        
        return response()->json([]);
    }
}
