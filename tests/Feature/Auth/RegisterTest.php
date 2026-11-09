<?php

/*test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
*/

it('creates a new account by register', function() {
    $response = $this->postJson('api/register', [
        'name' => 'Pedro',
        'email' => 'pedro@mail.com',
        'password' => '12345678'
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'pedro@mail.com']);
});

it('returns user data, no password, and a token', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Pedro',
        'email' => 'pedro@mail.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']])
        ->assertJsonMissingPath('user.password');
});