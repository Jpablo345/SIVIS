<?php

namespace Database\Factories;

use App\Models\AudiResearcher;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudiResearcherFactory extends Factory
{
    protected $model = AudiResearcher::class;

    public function definition(): array
    {
        return [
            'researcher_id' => (string) $this->faker->numberBetween(1, 99999999),
            'name_1' => $this->faker->firstName(),
            'last_name_1' => $this->faker->lastName(),
            'cod_minciencias' => 'COL' . $this->faker->numerify('#######'),
            'fecha_registro' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'usuario' => $this->faker->userName(),
            'accion' => $this->faker->randomElement(['U', 'D']),
        ];
    }
}
