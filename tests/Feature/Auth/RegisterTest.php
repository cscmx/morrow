<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;


it('creates a new account via register', function() {
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

it('stores the password - hashed', function () {
    $this->postJson('/api/register', [
        'name' => 'Pedro', 'email' => 'pedro@mail.com', 'password' => '12345678',
    ]);

    $user = User::where('email', 'pedro@mail.com')->first();

    expect($user->password)->not->toBe('12345678');
    expect(Hash::check('12345678', $user->password))->toBeTrue();
});

it('requires name, email and password', function () {
    $this->postJson('/api/register', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('rejects invalid email format', function () {
    $this->postJson('/api/register', [
        'name' => 'Pedro', 'email' => 'not-an-email', 'password' => '12345678',
    ])->assertStatus(422)->assertJsonValidationErrors(['email']);
});

it('rejects email that is already registered', function () {
    User::factory()->create(['email' => 'pedro@mail.com']);

    $this->postJson('/api/register', [
        'name' => 'Ana', 'email' => 'pedro@mail.com', 'password' => '12345678',
    ])->assertStatus(422)->assertJsonValidationErrors(['email']);
});

it('rejects password shorter than 8 characters', function () {
    $this->postJson('/api/register', [
        'name' => 'Pedro', 'email' => 'pedro@mail.com', 'password' => '1234567',
    ])->assertStatus(422)->assertJsonValidationErrors(['password']);
});