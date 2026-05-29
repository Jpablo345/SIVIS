<?php

namespace Database\Factories;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalFactory extends Factory
{
    protected $model = Journal::class;

    public function definition(): array
    {
        $suffix = $this->faker->boolean(10) ? 'X' : (string) $this->faker->numberBetween(0, 9);
        $issn = $this->faker->unique()->numerify('####-###') . $suffix;

        return [
            'journal_issn' => $issn,
            'journal_name' => $this->faker->unique()->sentence(3),
            'category' => $this->faker->randomElement(['Q1', 'Q2', 'Q3', 'Q4']),
        ];
    }
}
