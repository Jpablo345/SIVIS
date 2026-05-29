<?php

namespace Database\Factories;

use App\Models\AudiPublication;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudiPublicationFactory extends Factory
{
    protected $model = AudiPublication::class;

    public function definition(): array
    {
        return [
            'publication_id' => $this->faker->numberBetween(1, 100000),
            'title' => $this->faker->sentence(6),
            'publication_year' => (string) $this->faker->numberBetween(2000, 2026),
            'fecha_registro' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'usuario' => $this->faker->userName(),
            'accion' => $this->faker->randomElement(['U', 'D']),
        ];
    }
}
