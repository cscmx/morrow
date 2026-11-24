<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when updating a calendar without a token', function () {
    $calendar = Calendar::factory()->create();

    $this->putJson('/api/calendars/'.$calendar->id, ['name' => 'New calendar name'])->assertStatus(401);
});

it('returns 403 when a user updates the calendar of another user', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/calendars/'.$calendar->id, ['name' => 'New calendar name'])->assertStatus(403);
});
