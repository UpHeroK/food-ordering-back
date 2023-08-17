<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'invalid_credentials']);
    }

    public function testGetAuthenticatedUser()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/user');

                         //saber el valor de

        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $response->json());
    }

    public function testSuccessfulRegistration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201);
        $this->assertArrayHasKey('user', $response->json());
        $this->assertArrayHasKey('token', $response->json());
    }

    public function testRegistrationWithExistingEmail()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'El correo ya ha sido registrado']);
    }

    // Puedes añadir más pruebas para manejo de excepciones y otros escenarios.
}
