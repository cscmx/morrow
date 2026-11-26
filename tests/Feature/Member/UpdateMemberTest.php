<?php

use App\Models\Calendar;
use App\Models\Member;
use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when updating a member without a token', function () 
{
    $member = Member::factory()->create();
    $this->putJson('/api/members/'.$member->id, ['name' => 'New member name'])->assertStatus(401);
});

it('returns 403 when a user updates a member of the calendar of another user', function () {
    $user = User::factory()->create();
    $member = Member::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/members/'.$member->id, ['name' => 'New member name'])->assertStatus(403);
});