<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when listing calendars without a token', function () {
    $this->getJson('/api/calendars')->assertStatus(401);
});

