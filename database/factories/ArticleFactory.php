<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(2);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => fake()->text(),
            'image_path' => fake()->filePath(),
            'active' => fake()->boolean(),
        ];
    }
}
