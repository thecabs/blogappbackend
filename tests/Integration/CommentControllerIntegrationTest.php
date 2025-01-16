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
        $comment = Comment::factory()->create();
    
        $response = $this->put("/comments/{$comment->id}", [
            'comment' => 'Updated comment',
        ]);
    
        $response->assertStatus(200);
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
