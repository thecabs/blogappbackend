<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    // Teste la création d'un post
    public function test_create_post()
    {
        $user = User::factory()->create();
        $token = $user->createToken('secret')->plainTextToken; // Génère un token pour l'utilisateur

        $response = $this->postJson('/api/posts', [
            'body' => 'This is a test post', // Contenu du post
        ], [
            'Authorization' => 'Bearer ' . $token, // Ajoute l'en-tête d'authentification
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJsonStructure(['message', 'post']); // Vérifie la structure JSON
    }

    // Teste la récupération de tous les posts
    public function test_get_all_posts()
    {
        $user = User::factory()->create();
        Post::factory(5)->create(['user_id' => $user->id]); // Crée 5 posts pour l'utilisateur

        $response = $this->actingAs($user)->getJson('/api/posts');

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJsonStructure(['posts']); // Vérifie la structure JSON
    }

    // Teste la suppression d'un post
    public function test_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]); // Crée un post pour l'utilisateur

        $response = $this->actingAs($user)->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Post deleted.']); // Vérifie le message JSON
    }
}
