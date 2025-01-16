<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post()
    {
        $user = User::factory()->create();
        $token = $user->createToken('secret')->plainTextToken;

        $response = $this->postJson('/api/posts', [
            'body' => 'This is a test post',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'post']);
    }

    public function test_get_all_posts()
    {
        $user = User::factory()->create();
        Post::factory(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure(['posts']);
    }

    public function test_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Post deleted.']);
    }
}
