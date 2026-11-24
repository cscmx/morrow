<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when updating a calendar without a token', function () {
    $calendar = Calendar::factory()->create();

    $this->putJson('/api/calendars/'.$calendar->id, ['name' => 'New Name'])->assertStatus(401);
});

