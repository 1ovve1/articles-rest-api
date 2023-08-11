<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    function run(): void
    {
        $userIds = User::all()->pluck('id')->toArray();

        Author::factory()
            ->times(10)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => now()->addDays($sequence->index),
                'user_id' => $userIds[$sequence->index],
            ]))
            ->create();
    }
}
