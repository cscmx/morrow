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

it('returns 403 when a user updates a member of the calendar of another user', function () 
{
    $user = User::factory()->create();
    $member = Member::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/members/'.$member->id, ['name' => 'New member name'])->assertStatus(403);
});

it('returns 200 when the owner updates a member', function () 
{
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create(['calendar_id' => $calendar->id]);
    Passport::actingAs($user);

    $this->putJson('/api/members/'.$member->id, ['name' => 'New member name', 'color' => '#00FF00'])->assertStatus(200);

    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'name' => 'New member name',
        'color' => '#00FF00',
    ]);
});

it('returns 422 when updating a member without a name', function () {
    $user = User::factory()->create();
    $calendar = Calendar::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create(['calendar_id' => $calendar->id]);
    Passport::actingAs($user);

    $this->putJson('/api/members/'.$member->id, [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});