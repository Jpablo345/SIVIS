<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition(): array
    {
        return [
            'institution_name' => $this->faker->unique()->company() . ' University',
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'institution_type' => $this->faker->randomElement(['Public', 'Private', 'University']),
            'website' => $this->faker->optional()->url(),
        ];
    }
}
