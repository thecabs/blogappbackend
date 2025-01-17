<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    // Teste l'ajout d'un "like" à un post
    public function test_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $token = $user->createToken('secret')->plainTextToken; // Génère un token pour l'utilisateur

        $response = $this->postJson('/api/posts/' . $post->id . '/likes', [], [
            'Authorization' => 'Bearer ' . $token, // Ajoute l'en-tête d'authentification
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Liked']); // Vérifie le message JSON
    }

    // Teste le retrait d'un "like" d'un post (dislike)
    public function test_dislike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Like::create(['post_id' => $post->id, 'user_id' => $user->id]); // Ajoute un "like" initial au post
        $token = $user->createToken('secret')->plainTextToken; // Génère un token pour l'utilisateur

        $response = $this->postJson('/api/posts/' . $post->id . '/likes', [], [
            'Authorization' => 'Bearer ' . $token, // Ajoute l'en-tête d'authentification
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Disliked']); // Vérifie le message JSON
    }
}
