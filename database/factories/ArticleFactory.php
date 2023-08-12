<?php

namespace Database\Factories;

use App\Models\User;
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
        $users = User::all()->pluck('id')->toArray();

        return [
            'slug' => Str::slug($title),
            'user_id' => fake()->randomElement($users),
            'title' => $title,
            'content' => fake()->text(),
            'image_path' => fake()->filePath(),
            'active' => fake()->boolean(),
        ];
    }
}
