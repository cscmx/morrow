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
