<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Tests pour les fonctionnalités de gestion des commentaires
class CommentControllerTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données après chaque test

    /** @test */
    public function test_user_can_create_comment()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post sur lequel commenter
        $post = Post::factory()->create();

        // Données du commentaire
        $data = [
            'comment' => 'This is a test comment.',
        ];

        // Envoi d'une requête POST pour créer un commentaire
        $response = $this->postJson("/api/posts/{$post->id}/comments", $data);

        // Vérifie que le commentaire est créé avec succès
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Comment created.',
                 ]);
    }

    /** @test */
    public function test_user_cannot_create_comment_on_nonexistent_post()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Données du commentaire
        $data = [
            'comment' => 'This is a test comment.',
        ];

        // Tentative de commenter sur un post inexistant
        $response = $this->postJson("/api/posts/999/comments", $data);

        // Vérifie que l'erreur appropriée est renvoyée
        $response->assertStatus(403)
                 ->assertJson([
                     'message' => 'Post not found.',
                 ]);
    }

    /** @test */
    public function test_user_can_update_comment()
    {
        // Création d'un utilisateur et simulation de connexion
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création d'un post et d'un commentaire initial
        $post = Post::factory()->create();
        $comment = Comment::create([
            'comment' => 'Original comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Données de mise à jour du commentaire
        $data = [
            'comment' => 'Updated comment',
        ];

        // Envoi d'une requête PUT pour modifier le commentaire
        $response = $this->putJson("/api/comments/{$comment->id}", $data);

        // Vérifie que le commentaire est mis à jour avec succès
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Comment updated.',
                 ]);
    }
}
