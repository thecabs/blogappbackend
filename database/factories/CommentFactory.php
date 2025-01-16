<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'post_id' => \App\Models\Post::factory(), // Associe un post existant ou généré
            'user_id' => \App\Models\User::factory(), // Associe un utilisateur existant ou généré
        ];
    }
}
