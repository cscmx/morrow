<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a calendar without a token', function () {
    $this->postJson('/api/calendars', ['name' => 'Family'])->assertStatus(401);
});
