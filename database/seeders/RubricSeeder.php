<?php

namespace Database\Seeders;

use App\Models\Rubric;
use Faker\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        Rubric::factory()
            ->times(10)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => now()->addDays($sequence->index),
            ]))
            ->create();
    }
}
