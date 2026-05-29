<?php

namespace Database\Factories;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    protected $model = Publication::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'publication_year' => (string) $this->faker->numberBetween(2000, 2026),
            'scope' => $this->faker->randomElement(['Nacional', 'Internacional']),
            'country_publication' => $this->faker->country(),
            'url' => $this->faker->optional()->url(),
            'type_id' => null,
        ];
    }
}
