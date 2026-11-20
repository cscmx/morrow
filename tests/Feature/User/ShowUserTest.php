<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when showing a user without a token', function () {
    $user = User::factory()->create();
    $this->getJson('/api/users/'.$user->id)->assertStatus(401);
});

it('returns 403 when a normal user shows another user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $other = User::factory()->create();
    Passport::actingAs($user);

    $this->getJson('/api/users/'.$other->id)->assertStatus(403);
});

it('returns 200 when an admin shows another user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $other = User::factory()->create();
    Passport::actingAs($admin);

    $this->getJson('/api/users/'.$other->id)->assertStatus(200)->assertJsonPath('id', $other->id);
});

it('returns 200 when a normal user shows themselves', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Passport::actingAs($user);

    $this->getJson('/api/users/'.$user->id)->assertStatus(200)->assertJsonPath('id', $user->id);
});

it('returns 404 when the user does not exist', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Passport::actingAs($admin);

    $this->getJson('/api/users/9999')->assertStatus(404);
});