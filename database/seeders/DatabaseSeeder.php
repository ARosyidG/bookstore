<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Category;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $this->seedCategories();
        $this->seedAuthors();
        $this->seedBooks();
        $this->seedRatings();
    }

    protected function seedCategories(): void
    {
        Category::factory(3000)->create();
    }

    protected function seedAuthors(): void
    {
        Author::factory(1000)->create();
    }

    protected function seedBooks(): void
    {
        $this->bulkInsertWithFactory(Book::factory(), 100_000, 'books', 10_000);
    }

    protected function seedRatings(): void
    {

        $this->bulkInsertWithFactory(Rating::factory(), 500_000, 'ratings', 10_000);
    }

    // create in batches to avoid memory issues
    protected function bulkInsertWithFactory($factory, int $total, string $table, int $batchSize): void
    {
        $now = Carbon::now();
        for ($i = 0; $i < $total; $i += $batchSize) {
            $current = min($batchSize, $total - $i);
            $rows = $factory->count($current)->make()->map(function ($model) use ($now) {
                $attrs = $model->getAttributes();
                unset($attrs['id']);
                $attrs['created_at'] = $attrs['created_at'] ?? $now;
                $attrs['updated_at'] = $attrs['updated_at'] ?? $now;
                return $attrs;
            })->all();

            DB::table($table)->insert($rows);
            unset($rows);
        }
    }
}
