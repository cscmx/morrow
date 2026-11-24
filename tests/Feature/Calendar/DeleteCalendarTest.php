<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when deleting a calendar without a token', function () 
{
    $calendar = Calendar::factory()->create();
    $this->deleteJson('/api/calendars/'.$calendar->id)->assertStatus(401);
});

it('returns 403 when a user deletes the calendar of another user', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->deleteJson('/api/calendars/'.$calendar->id)->assertStatus(403);
});

it('returns 204 when the owner deletes their calendar', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    Passport::actingAs($user);

    $this->deleteJson('/api/calendars/'.$calendar->id)->assertStatus(204);

    $this->assertDatabaseMissing('calendars', ['id' => $calendar->id]);
});