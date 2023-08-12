<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
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
        $rubricIds = $this->getIdsFromCollection(Rubric::all());
        $articleIds = $this->getIdsFromCollection(Article::all());

        return [
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
