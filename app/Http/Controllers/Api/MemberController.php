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
            'color' => ['sometimes', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $calendar = Calendar::find($validatedData['calendar_id']);
        Gate::authorize('create', [Member::class, $calendar]);
        unset($validatedData['calendar_id']);

        $member = $calendar->members()->create($validatedData);
        $member->refresh();

        return response()->json(['member' => $member], 201);
    }

    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'calendar_id' => ['required', 'exists:calendars,id'],
        ]);
        $calendar = Calendar::find($validatedData['calendar_id']);
        Gate::authorize('view', $calendar);

        return response()->json($calendar->members);
    }

    public function update(Request $request, Member $member)
    {
        Gate::authorize('update', $member);
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'category' => ['nullable', 'string', 'max:50'],
            'color' => ['sometimes', 'string', 'max:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);
        $member->update($validatedData);
        return response()->json(['member' => $member]);
    }

    public function destroy(Member $member)
    {
        Gate::authorize('delete', $member);
        $member->delete();
        return response()->noContent();
    }
}
