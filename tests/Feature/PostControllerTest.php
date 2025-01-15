<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_create_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'body' => 'This is a test post.',
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post created.',
                 ]);
    }

    /** @test */
    public function test_user_can_update_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $data = [
            'body' => 'Updated post content.',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post updated.',
                 ]);
    }

    /** @test */
    public function test_user_can_delete_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Post deleted.',
                 ]);
    }
}
