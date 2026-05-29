<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $bookTypeIds = BookType::pluck('book_type_id')->all();

        if (empty($bookTypeIds)) {
            $this->command?->warn('No book types found. Seed book types before books.');
            return;
        }

        $typeIds = PublicationType::pluck('type_id')->all();

        foreach (range(1, 5) as $i) {
            $publication = Publication::factory()->create([
                'type_id' => $typeIds ? Arr::random($typeIds) : null,
            ]);

            Book::create([
                'publication_id' => $publication->publication_id,
                'book_isbn' => fake()->unique()->numerify('978-###-#####-###-#'),
                'means_of_dissemination' => fake()->randomElement(['Digital', 'Impreso', 'Digital e Impreso']),
                'editorial' => fake()->company(),
                'book_type_id' => Arr::random($bookTypeIds),
            ]);
        }
    }
}
