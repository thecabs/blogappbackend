<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Tests pour vérifier les fonctionnalités de like et unlike des posts
class LikeControllerTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données après chaque test

    /** @test */
    public function user_can_like_a_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post à liker
        $post = Post::factory()->create();

        // Envoi d'une requête POST pour liker un post
        $response = $this->postJson("/api/posts/{$post->id}/likes");

        // Vérifie que le like est enregistré correctement
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Liked'
            ]);

        // Vérifie dans la base de données que le like existe
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_can_unlike_a_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post et ajout d'un like
        $post = Post::factory()->create();
        $post->likes()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        // Envoi d'une requête DELETE pour enlever le like
        $response = $this->deleteJson("/api/posts/{$post->id}/likes");

        // Vérifie que le like est supprimé
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Disliked'
            ]);

        // Vérifie dans la base de données que le like n'existe plus
        $this->assertDatabaseMissing('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_cannot_like_a_non_existent_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Tentative de liker un post inexistant
        $response = $this->postJson('/api/posts/999/likes');

        // Vérifie que la réponse retourne un code 404 avec un message approprié
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Post not found.'
            ]);
    }

    /** @test */
    public function user_must_be_authenticated_to_like_or_unlike_a_post()
    {
        // Création d'un post
        $post = Post::factory()->create();

        // Tentative de liker sans être authentifié
        $response = $this->postJson("/api/posts/{$post->id}/likes");

        // Vérifie que l'utilisateur non authentifié ne peut pas liker
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);

        // Tentative de unliker sans être authentifié
        $response = $this->deleteJson("/api/posts/{$post->id}/likes");

        // Vérifie que l'utilisateur non authentifié ne peut pas unliker
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
