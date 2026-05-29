<?php

namespace Database\Factories;

use App\Models\BookType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookTypeFactory extends Factory
{
    protected $model = BookType::class;

    public function definition(): array
    {
        return [
            'type_name' => $this->faker->unique()->words(3, true),
        ];
    }
}
