<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostsTags;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'author_id' => \App\Models\User::factory(), // Relaciona automaticamente com um usuário
        ];
    }

    public function withTags($count = 3)
    {
        return $this->has(PostsTags::factory()->count($count), 'tags'); // Adiciona 'count' tags para o post
    }
}
