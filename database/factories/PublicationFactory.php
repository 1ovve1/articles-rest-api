<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Author;
use App\Models\Rubric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publication>
 */
class PublicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authorIds = $this->getIdsFromCollection(Author::all());
        $rubricIds = $this->getIdsFromCollection(Rubric::all());
        $articleIds = $this->getIdsFromCollection(Article::all());

        return [
            'author_id' => fake()->randomElement($authorIds),
            'rubric_id' => fake()->randomElement($rubricIds),
            'article_id' => fake()->randomElement($articleIds)
        ];
    }

    /**
     * @param Collection $collection
     * @return array
     */
    function getIdsFromCollection(Collection $collection): array
    {
        return $collection->pluck('id')->toArray();
    }
}
