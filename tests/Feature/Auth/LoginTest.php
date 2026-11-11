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

it('rejects a wrong password', function () 
{    
	User::factory()->create(['email' => 'pedro@mail.com', 'password' => '12345678']);    
		$this->postJson('/api/login', [        
			'email' => 'pedro@mail.com',        
			'password' => 'wrong-password',    ])        
	->assertStatus(401)        
	->assertJson(['message' => 'Invalid credentials.']);
});
		
		
it('rejects an email that does not exist', function () 
{    
	$this->postJson('/api/login', [        
		'email' => 'nobody@mail.com',       
        'password' => '12345678',    ])        
	->assertStatus(401)       
	->assertJson(['message' => 'Invalid credentials.']);
});


it('requires email and password', function () 
{    
	$this->postJson('/api/login', [])        
	->assertStatus(422)        
	->assertJsonValidationErrors(['email', 'password']);
});
