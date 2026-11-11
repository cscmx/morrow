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

    $auth = $this->app['auth'];
    $reflection = new \ReflectionProperty($auth, 'guards');
    $reflection->setAccessible(true);
    $reflection->setValue($auth, []);

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

    $auth = $this->app['auth'];
    $reflection = new \ReflectionProperty($auth, 'guards');
    $reflection->setAccessible(true);
    $reflection->setValue($auth, []);

    $this->withToken($tokenA)
        ->getJson('/api/user')
        ->assertStatus(401);

    $auth = $this->app['auth'];
    $reflection = new \ReflectionProperty($auth, 'guards');
    $reflection->setAccessible(true);
    $reflection->setValue($auth, []);

    $this->withToken($tokenB)
        ->getJson('/api/user')
        ->assertStatus(200);
});