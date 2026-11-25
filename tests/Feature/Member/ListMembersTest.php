<?php

use App\Models\Calendar;
use App\Models\Member;
use App\Models\User;
use Laravel\Passport\Passport;

it('it returns 401 when listing the members without a token', function() 
{
    $calendar = Calendar::factory()->create();
    $this->getJson('/api/members?calendar_id='.$calendar->id)->assertStatus(401);
});