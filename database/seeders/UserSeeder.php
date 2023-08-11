<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    function run(): void
    {
        $roles = Role::all()->pluck('name')->toArray();

        User::factory()
            ->times(10)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => now()->addDays($sequence->index),
                ]))
            ->afterCreating(fn (User $user) =>
                $user->assignRole(fake()->randomElement($roles))
                )
            ->create();
    }
}
