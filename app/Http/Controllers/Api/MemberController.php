<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Member; 
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'calendar_id' => ['required', 'exists:calendars,id'],
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'category' => ['nullable', 'string', 'max:50'],
            'color' => ['sometimes', 'string', 'max:7'],
        ]);

        $calendar = Calendar::find($validatedData['calendar_id']);
        Gate::authorize('create', [Member::class, $calendar]);

        return response()->json([]);
    }
}
