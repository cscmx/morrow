<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when listing users without a token', function () {
    $this->getJson('/api/users')->assertStatus(401);
});

it('returns 403 when a normal user lists users', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Passport::actingAs($user);

    $this->getJson('/api/users')->assertStatus(403);
});