<?php

use App\Models\Calendar;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a member without a token', function () 
{
   $calendar = Calendar::factory()->create();
    $this->postJson('/api/members', ['calendar_id' => $calendar->id, 'name' => 'Pedro'])->assertStatus(401);

});