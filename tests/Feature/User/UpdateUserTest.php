<?php

use App\Models\User;
use Laravel\Passport\Passport;

it('return 401 when updating a user without a token', function(){
    $user = User::factory()->create();
    $this->putJson('/api/users/'.$user->id, ['name' => 'new name'])->assertStatus(401);
});


it('returns 403 when a normal user updates another user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $other = User::factory()->create();
    Passport::actingAs($user);

    $this->putJson('/api/users/'.$other->id, ['name' => 'New Name'])->assertStatus(403);
});

it('returns 200 when an admin updates another user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $other = User::factory()->create();
    Passport::actingAs($admin);

    $this->putJson('/api/users/'.$other->id, ['name' => 'New Name'])->assertStatus(200);

    $this->assertDatabaseHas('users', ['id' => $other->id, 'name' => 'New Name']);
});