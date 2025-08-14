<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    private static ?array $bookIds = null;

    public function definition(): array
    {
        self::$bookIds ??= Book::pluck('id')->all();

        if (empty(self::$bookIds)) {
            throw new \RuntimeException('No books available to assign ratings to.');
        }

        return [
            'book_id' => self::$bookIds[array_rand(self::$bookIds)],
            'rating'  => $this->faker->numberBetween(1, 10),
        ];
    }
}
