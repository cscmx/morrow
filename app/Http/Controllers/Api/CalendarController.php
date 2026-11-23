<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\User;

class CalendarController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['sometimes', 'string', 'max:7'],
        ]);
        $calendar = $request->user()->calendars()->create($validatedData);
        $calendar->refresh();

        return response()->json(['calendar' => $calendar], 201);
    }

    public function index()
    {
        return response()->json([]);
    }
}
