<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name'  => fake()->firstName(),
            'last_name'   => fake()->lastName(),
            'nationality' => fake()->randomElement(['italiana', 'francese', 'inglese', 'spagnola']),
            'birth_year'  => fake()->numberBetween(1850, 1990),
        ];
    }
}
