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