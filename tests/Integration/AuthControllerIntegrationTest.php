<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    // Teste l'inscription d'un utilisateur
    public function test_register_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJsonStructure(['user', 'token']); // Vérifie la structure JSON
    }

    // Teste la connexion d'un utilisateur
    public function test_login_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password') // Création de l'utilisateur avec un mot de passe crypté
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJsonStructure(['user', 'token']); // Vérifie la structure JSON
    }

    // Teste la déconnexion d'un utilisateur
    public function test_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('secret')->plainTextToken; // Génère un token pour l'utilisateur

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token, // En-tête avec le token
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Logout success.']); // Vérifie le message JSON
    }
}
