<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('return 401 when deleting a user without a token', function()
{
    $user = User::factory()->create();
    $this->deleteJson('/api/users/'.$user->id)->assertStatus(401);
});