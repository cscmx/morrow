<?php

use App\Models\Calendar;
use App\Models\Member;
use App\Models\User;
use Laravel\Passport\Passport;

it('it returns 401 when deleting a member without a token', function()
{
    $member = Member::factory()->create();
    return $this->deleteJson('api/members/'.$member->id)->assertStatus(401);
});

it('returns 403 when a user deletes a member of the calendar of another user', function () 
{
    $user = User::factory()->create();
    $member = Member::factory()->create();
    Passport::actingAs($user);

    $this->deleteJson('/api/members/'.$member->id)->assertStatus(403);
});

it('returns 204 when the owner deletes a member', function () 
{
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create(['calendar_id' => $calendar->id]);
    Passport::actingAs($user);

    $this->deleteJson('/api/members/'.$member->id)->assertStatus(204);

    $this->assertDatabaseMissing('members', ['id' => $member->id]);
});

it('returns 404 when deleting a member that does not exist', function () 
{
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->deleteJson('/api/members/9999')->assertStatus(404);
});