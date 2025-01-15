<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_create_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $data = [
            'comment' => 'This is a test comment.',
        ];

        $response = $this->postJson("/api/posts/{$post->id}/comments", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Comment created.',
                 ]);
    }

    /** @test */
    public function test_user_cannot_create_comment_on_nonexistent_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'comment' => 'This is a test comment.',
        ];

        $response = $this->postJson("/api/posts/999/comments", $data);

        $response->assertStatus(403)
                 ->assertJson([
                     'message' => 'Post not found.',
                 ]);
    }

    /** @test */
    public function test_user_can_update_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();
        $comment = Comment::create([
            'comment' => 'Original comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $data = [
            'comment' => 'Updated comment',
        ];

        $response = $this->putJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Comment updated.',
                 ]);
    }
}
