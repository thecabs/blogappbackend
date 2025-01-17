<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Tests pour vérifier les fonctionnalités CRUD des posts
class PostControllerTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données après chaque test

    /** @test */
    public function test_user_can_create_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Données pour créer un post
        $data = [
            'body' => 'This is a test post.',
        ];

        // Envoi d'une requête POST pour créer un post
        $response = $this->postJson('/api/posts', $data);

        // Vérifie que le post est créé avec succès
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post created.',
                 ]);
    }

    /** @test */
    public function test_user_can_update_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post appartenant à l'utilisateur
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Données pour mettre à jour le post
        $data = [
            'body' => 'Updated post content.',
        ];

        // Envoi d'une requête PUT pour mettre à jour le post
        $response = $this->putJson("/api/posts/{$post->id}", $data);

        // Vérifie que le post est mis à jour avec succès
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post updated.',
                 ]);
    }

    /** @test */
    public function test_user_can_delete_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post appartenant à l'utilisateur
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Envoi d'une requête DELETE pour supprimer le post
        $response = $this->deleteJson("/api/posts/{$post->id}");

        // Vérifie que le post est supprimé avec succès
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post deleted.',
                 ]);
    }
}
