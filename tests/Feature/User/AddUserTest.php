<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('returns 401 when creating a user without a token', function () {
   
    $this->postJson('/api/users')->assertStatus(401);

});


it ('returns 403 when a normal user creates a new user', function() {
    $user = User::factory()->create();
    $user->assignRole('user');

    Passport::actingAs($user);

    $this->postJson('/api/users')->assertStatus(403);
});

it('returns 201 when an admin creates and store a new user', function(){
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Passport::actingAs($admin);

    $this->postJson('/api/users', [
        'name' => 'Ana', 'email' => 'ana@mail.com', 'password' => 'password123',
    ])->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'ana@mail.com']);

});