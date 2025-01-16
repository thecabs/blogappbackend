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

    public function test_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $token = $user->createToken('secret')->plainTextToken;

        $response = $this->postJson('/api/posts/' . $post->id . '/likes', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Liked']);
    }

    public function test_dislike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Like::create(['post_id' => $post->id, 'user_id' => $user->id]);
        $token = $user->createToken('secret')->plainTextToken;

        $response = $this->postJson('/api/posts/' . $post->id . '/likes', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Disliked']);
    }
}
