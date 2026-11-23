<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a calendar without a token', function () {
    $this->postJson('/api/calendars', ['name' => 'Family'])->assertStatus(401);
});

it('returns 201 when a user creates a calendar', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $this->postJson('/api/calendars', ['name' => 'Family', 'color' => '#DC2626'])->assertStatus(201);
    $this->assertDatabaseHas('calendars', [
        'name' => 'Family',
        'color' => '#DC2626',
        'user_id' => $user->id,
    ]);
});

it('returns 422 when creating a calendar without a name', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $this->postJson('/api/calendars', [])->assertStatus(422)->assertJsonValidationErrors(['name']);
});

it('uses the default color when none is sent', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $this->postJson('/api/calendars', ['name' => 'Work'])->assertStatus(201);
    $this->assertDatabaseHas('calendars', ['name' => 'Work', 'color' => '#93c5fd']);
});

it('ignores a user_id sent in the body', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Passport::actingAs($user);
    $this->postJson('/api/calendars', ['name' => 'Mine', 'user_id' => $other->id])->assertStatus(201);
    $this->assertDatabaseHas('calendars', ['name' => 'Mine', 'user_id' => $user->id]);
});