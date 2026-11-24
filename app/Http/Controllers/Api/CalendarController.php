<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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

    public function index(Request $request)
    {
        return response()->json($request->user()->calendars);
    }

    public function show(Calendar $calendar)
    {
        Gate::authorize('view',$calendar);
        return response()->json($calendar);
    }

    public function update(Request $request, Calendar $calendar)
    {
        Gate::authorize('update',$calendar);
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['sometimes', 'string', 'max:7'],
        ]);
        $calendar->update($validatedData);

        return response()->json(['calendar' => $calendar]);
    }
}
