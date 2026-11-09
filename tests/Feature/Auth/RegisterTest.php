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
