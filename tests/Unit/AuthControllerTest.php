<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// Classe de tests pour vérifier les fonctionnalités d'authentification
class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données après chaque test

    /** @test */
    public function test_user_can_register()
    {
        // Données pour l'inscription d'un utilisateur
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        // Envoi d'une requête POST pour s'inscrire
        $response = $this->postJson('/api/register', $data);

        // Vérifie que la réponse est correcte et contient les structures attendues
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'token',
                 ]);
    }

    /** @test */
    public function test_user_can_login()
    {
        // Création d'un utilisateur avec un mot de passe
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
        ]);

        // Données pour la connexion
        $data = [
            'email' => $user->email,
            'password' => 'secret123',
        ];

        // Envoi d'une requête POST pour se connecter
        $response = $this->postJson('/api/login', $data);

        // Vérifie que la réponse est correcte et contient les structures attendues
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'token',
                 ]);
    }

    /** @test */
    public function test_user_can_logout()
    {
        // Création d'un utilisateur et simulation d'une session active
        $user = User::factory()->create();
        $this->actingAs($user);

        // Envoi d'une requête POST pour se déconnecter
        $response = $this->postJson('/api/logout');

        // Vérifie que la déconnexion est réussie
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Logout success.',
                 ]);
    }
}
