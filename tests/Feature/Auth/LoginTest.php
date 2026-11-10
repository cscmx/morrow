<?php
use App\Models\User;

it('logs in with email and password', function() {
    User::factory()->create([
        'email' => 'pedro@mail.com',
        'password' => '12345678',
    ]);

    $this->postJson('/api/login', [
        'email' => 'pedro@mail.com',
        'password' => '12345678',
    ])
    ->assertStatus(200)
    ->assertJsonStructure(['token', 'user' => ['id','name','email']])
    ->assertJsonMissingPath('user.password');


});