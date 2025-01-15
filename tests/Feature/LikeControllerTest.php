<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_like_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $response = $this->postJson("/api/posts/{$post->id}/likes");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Liked'
            ]);

        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
  /** @test */
public function user_can_unlike_a_post()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->create();
    $post->likes()->create([
        'post_id' => $post->id,
        'user_id' => $user->id
    ]);

    $response = $this->deleteJson("/api/posts/{$post->id}/likes");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Disliked'
        ]);

    $this->assertDatabaseMissing('likes', [
        'post_id' => $post->id,
        'user_id' => $user->id
    ]);
}


    /** @test */
  // Test pour le like d'un post inexistant
public function user_cannot_like_a_non_existent_post()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('/api/posts/999/likes');  // Utilise un ID de post inexistant

    $response->assertStatus(404)  // Attends un code 404 ici
        ->assertJson([
            'message' => 'Post not found.'
        ]);
}


    /** @test */
    public function user_must_be_authenticated_to_like_or_unlike_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->postJson("/api/posts/{$post->id}/likes");

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);

        $response = $this->deleteJson("/api/posts/{$post->id}/likes");

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
