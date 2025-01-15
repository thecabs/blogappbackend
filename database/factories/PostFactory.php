<?php
namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Le nom du modèle associé à la factory.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Définir l'état par défaut de l'élément modèle.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->text,  // Exemple d'attribut pour un post
            'user_id' => \App\Models\User::factory(),  // Utilise une factory pour le user lié
        ];
    }
}
