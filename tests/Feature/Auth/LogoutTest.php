<?php

use App\Models\User;

it('logs out the user, revokes current token', function() 
{
    $user = User::factory()->create();
    $token = $user->createToken('test')->accessToken;
    
    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertStatus(200)
        ->assertJson(['message' => 'Logged out']);

    resetAuthGuards($this->app);

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertStatus(401);
});

it('returns 401 when logging out without a token', function()
{
    $this->postJson('/api/logout')
    ->assertStatus(401);
});

it('does not revoke other tokens when logging out', function () {
    $user   = User::factory()->create();
    $tokenA = $user->createToken('device-a')->accessToken;
    $tokenB = $user->createToken('device-b')->accessToken;

    $this->withToken($tokenA)
        ->postJson('/api/logout')
        ->assertStatus(200);

    resetAuthGuards($this->app);

    $this->withToken($tokenA)
        ->getJson('/api/user')
        ->assertStatus(401);

    resetAuthGuards($this->app);

    $this->withToken($tokenB)
        ->getJson('/api/user')
        ->assertStatus(200);
});