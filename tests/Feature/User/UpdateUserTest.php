<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('return 401 when updating a user without a token', function(){
    $user = User::factory()->create();
    $this->putJson('/api/users/'.$user->id, ['name' => 'new name'])->assertStatus(401);
});