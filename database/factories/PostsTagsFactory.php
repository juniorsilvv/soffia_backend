<?php

namespace Database\Factories;

use App\Models\PostsTags;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostsTagsFactory extends Factory
{
    protected $model = PostsTags::class;

    public function definition()
    {
        return [
            'tag_name' => $this->faker->word, // Gerando um nome de tag
        ];
    }
}
