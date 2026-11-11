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