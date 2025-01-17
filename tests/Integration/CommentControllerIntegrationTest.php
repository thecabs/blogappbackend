<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    // Teste la création d'un commentaire
    public function test_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $token = $user->createToken('secret')->plainTextToken; // Génère un token pour l'utilisateur

        $response = $this->postJson('/api/posts/' . $post->id . '/comments', [
            'comment' => 'This is a test comment',
        ], [
            'Authorization' => 'Bearer ' . $token, // Ajoute l'en-tête d'authentification
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Comment created.']); // Vérifie le message JSON
    }

    // Teste la mise à jour d'un commentaire
    public function testUpdateComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Authentifie l'utilisateur

        $comment = Comment::factory()->create(['user_id' => $user->id]); // Crée un commentaire lié à l'utilisateur

        $response = $this->putJson('/api/comments/' . $comment->id, [
            'comment' => 'Updated comment', // Nouveau contenu du commentaire
        ]);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Comment updated.']); // Vérifie le message JSON

        $this->assertDatabaseHas('comments', [ // Vérifie que la base de données a été mise à jour
            'id' => $comment->id,
            'comment' => 'Updated comment',
        ]);
    }

    // Teste la suppression d'un commentaire
    public function test_delete_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]); // Crée un commentaire lié à l'utilisateur

        $response = $this->actingAs($user)->deleteJson('/api/comments/' . $comment->id);

        $response->assertStatus(200) // Vérifie le code de statut
                 ->assertJson(['message' => 'Comment deleted.']); // Vérifie le message JSON
    }
}
