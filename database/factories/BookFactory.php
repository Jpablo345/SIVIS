<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'book_isbn' => $this->faker->unique()->numerify('978-###-#####-###-#'),
            'means_of_dissemination' => $this->faker->randomElement(['Digital', 'Impreso', 'Digital e Impreso']),
            'editorial' => $this->faker->company(),
            'book_type_id' => BookType::factory(),
        ];
    }
}
