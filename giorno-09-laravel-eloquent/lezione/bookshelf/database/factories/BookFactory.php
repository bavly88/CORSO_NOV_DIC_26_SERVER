<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'    => rtrim(fake()->sentence(3), '.'),
            'isbn'     => fake()->unique()->isbn13(),
            'year'     => fake()->numberBetween(1900, 2026),
            'pages'    => fake()->numberBetween(80, 900),
            // Usa un genere esistente; se la tabella e' vuota ne crea uno
            'genre_id' => fn () => Genre::inRandomOrder()->value('id') ?? Genre::factory(),
        ];
    }
}
