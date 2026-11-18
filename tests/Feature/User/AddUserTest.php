<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a user without a token', function () {
   
    $this->postJson('/api/users')->assertStatus(401);

});


it ('returns 403 when a normal user creates a new user', function() {
    $user = User::factory()->create();
    $user->assignRole('user');

    Passport::actingAs($user);

    $this->postJson('/api/users')->assertStatus(403);
});