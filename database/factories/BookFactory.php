<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    // Cache pools once
    private static ?array $authorIds = null;
    private static ?array $categoryIds = null;
    private static int $seq = 1; // cheap uniqueness for titles

    public function definition(): array
    {
        self::$authorIds   ??= Author::pluck('id')->all();
        self::$categoryIds ??= Category::pluck('id')->all();

        if (empty(self::$authorIds) || empty(self::$categoryIds)) {
            throw new \RuntimeException('No authors or categories available to create books.');
        }

        // Avoid Faker unique() for huge volumes (it keeps a big set)
        $title = 'Book ' . (self::$seq++) . ' — ' . $this->faker->words(4, true);

        return [
            'title'       => $title,
            'author_id'   => self::$authorIds[array_rand(self::$authorIds)],
            'category_id' => self::$categoryIds[array_rand(self::$categoryIds)],
        ];
    }
}
