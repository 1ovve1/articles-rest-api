<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::factory()
            ->times(20)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => now()->addDays($sequence->index),
            ]))
            ->create();
    }
}
