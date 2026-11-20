<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('return 401 when deleting a user without a token', function()
{
    $user = User::factory()->create();
    $this->deleteJson('/api/users/'.$user->id)->assertStatus(401);
});

it('returns 403 when a normal user deletes another user', function () 
{
    $user = User::factory()->create();
    $user->assignRole('user');
    $other = User::factory()->create();
    Passport::actingAs($user);

    $this->deleteJson('/api/users/'.$other->id)->assertStatus(403);
});

it('returns 204 when an admin deletes another user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $other = User::factory()->create();
    Passport::actingAs($admin);

    $this->deleteJson('/api/users/'.$other->id)->assertStatus(204);

    $this->assertDatabaseMissing('users', ['id' => $other->id]);
});

it('returns 204 when a normal user deletes themselves', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Passport::actingAs($user);

    $this->deleteJson('/api/users/'.$user->id)->assertStatus(204);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});