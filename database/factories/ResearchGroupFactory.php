<?php

namespace Database\Factories;

use App\Models\ResearchGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchGroupFactory extends Factory
{
    protected $model = ResearchGroup::class;

    public function definition(): array
    {
        return [
            'cod_minciencias' => 'COL' . $this->faker->unique()->numerify('#######'),
            'group_name' => $this->faker->unique()->company(),
            'group_classification' => $this->faker->randomElement(['A1', 'A', 'B', 'C', 'Reconocido']),
            'institution_id' => null,
        ];
    }
}
