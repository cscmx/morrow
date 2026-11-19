<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when showing a user without a token', function () {
    $user = User::factory()->create();
    $this->getJson('/api/users/'.$user->id)->assertStatus(401);
});