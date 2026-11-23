<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when showing a calendar without a token', function () {
    $calendar = Calendar::factory()->create();
    $this->getJson('/api/calendars/'.$calendar->id)->assertStatus(401);
});

it('returns 403 when a user shows the calendar of another user', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->getJson('/api/calendars/'.$calendar->id)->assertStatus(403);
});