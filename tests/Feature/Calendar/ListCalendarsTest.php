<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when listing calendars without a token', function () {
    $this->getJson('/api/calendars')->assertStatus(401);
});

it('returns only the calendars of the authenticated user', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Calendar::factory()->count(2)->create(['user_id' => $user->id]);
    Calendar::factory()->create(['user_id' => $other->id]);
    Passport::actingAs($user);

    $this->getJson('/api/calendars')->assertStatus(200)->assertJsonCount(2);
});