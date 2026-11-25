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

it('returns 403 when a user lists the members of the calendar of another user', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create();
    Passport::actingAs($user);

    $this->getJson('/api/members?calendar_id='.$calendar->id)->assertStatus(403);
});

it('returns only the members of the requested calendar', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    $otherCalendar = Calendar::factory()->create(['user_id' => $user->id]);
    Member::factory()->count(2)->create(['calendar_id' => $calendar->id]);
    Member::factory()->create(['calendar_id' => $otherCalendar->id]);
    Passport::actingAs($user);

    $this->getJson('/api/members?calendar_id=' . $calendar->id)->assertStatus(200)->assertJsonCount(2);
});
