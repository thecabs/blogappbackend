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

    public function test_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $token = $user->createToken('secret')->plainTextToken;

        $response = $this->postJson('/api/posts/' . $post->id . '/comments', [
            'comment' => 'This is a test comment',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Comment created.']);
    }

    public function testUpdateComment()
{
    // Create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a comment belonging to the authenticated user
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    // Send a PUT request to update the comment
    $response = $this->putJson('/api/comments/' . $comment->id, [
        'comment' => 'Updated comment',
    ]);

    // Assert the response status and structure
    $response->assertStatus(200)
    ->assertJson(['message' => 'Comment updated.']);


             
    // Verify the comment was updated in the database
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'comment' => 'Updated comment',
    ]);
}


    
    public function test_delete_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/comments/' . $comment->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Comment deleted.']);
    }
}
