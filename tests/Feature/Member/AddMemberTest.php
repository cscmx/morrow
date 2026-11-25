<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a member without a token', function () 
{
   $calendar = Calendar::factory()->create();
    $this->postJson('/api/members', ['calendar_id' => $calendar->id, 'name' => 'Pedro'])->assertStatus(401);

});

it('returns 403 when a user creates a member in the calendar of another user', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->postJson('/api/members', ['calendar_id' => $calendar->id, 'name' => 'Pedro'])->assertStatus(403);
});

it('returns 201 when the owner creates a member in their calendar', function () 
{
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    Passport::actingAs($user);

    $this->postJson('/api/members', [
        'calendar_id' => $calendar->id,
        'name' => 'Pedro',
        'date_of_birth' => '2015-04-20',
        'category' => 'child',
        'color' => '#ef4444',
    ])->assertStatus(201);

    $this->assertDatabaseHas('members', [
        'calendar_id' => $calendar->id,
        'name' => 'Pedro',
        'category' => 'child',
        'color' => '#ef4444',
    ]);
});

it('returns 422 when creating a member without data', function () 
{
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->postJson('/api/members', [])->assertStatus(422)->assertJsonValidationErrors(['calendar_id', 'name']);
});

it('returns 422 when the calendar does not exist', function () 
{
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->postJson('/api/members', ['calendar_id' => 9999, 'name' => 'Pedro'])->assertStatus(422)->assertJsonValidationErrors(['calendar_id']);
});

it('uses the default color when none is sent', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    Passport::actingAs($user);

    $this->postJson('/api/members', ['calendar_id' => $calendar->id, 'name' => 'Pedro'])->assertStatus(201);

    $this->assertDatabaseHas('members', ['name' => 'Pedro', 'color' => '#93c5fd']);
});