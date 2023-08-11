<?php

namespace Database\Seeders;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        Publication::factory()
            ->times(100)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => now()->addDays($sequence->index),
            ]))
            ->create();
    }
}
