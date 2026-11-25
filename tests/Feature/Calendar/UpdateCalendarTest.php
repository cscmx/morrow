<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when updating a calendar without a token', function () 
{
    $calendar = Calendar::factory()->create();

    $this->putJson('/api/calendars/'.$calendar->id, ['name' => 'New calendar name'])->assertStatus(401);
});

it('returns 403 when a user updates the calendar of another user', function () 
{
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/calendars/'.$calendar->id, ['name' => 'New calendar name'])->assertStatus(403);
});

it('returns 200 when the owner updates their calendar', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    Passport::actingAs($user);

    $this->putJson('/api/calendars/' . $calendar->id, ['name' => 'New calendar name', 'color' => '#86efac'])->assertStatus(200);

    $this->assertDatabaseHas('calendars', [
        'id' => $calendar->id,
        'name' => 'New calendar name',
        'color' => '#86efac',
    ]);
});

it('returns 422 when updating a calendar without a name', function () 
{
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    Passport::actingAs($user);

    $this->putJson('/api/calendars/'.$calendar->id, [])->assertStatus(422)->assertJsonValidationErrors(['name']);
});

it('returns 404 when updating a calendar that does not exist', function () 
{
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/calendars/9999', ['name' => 'New calendar name'])->assertStatus(404);
});
