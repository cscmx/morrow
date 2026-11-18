<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a user without a token', function () {
   
    $this->postJson('/api/users')->assertStatus(401);

});