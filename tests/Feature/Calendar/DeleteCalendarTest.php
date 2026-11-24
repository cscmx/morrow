<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when deleting a calendar without a token', function () 
{
    $calendar = Calendar::factory()->create();
    $this->deleteJson('/api/calendars/'.$calendar->id)->assertStatus(401);
});

