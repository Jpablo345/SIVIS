<?php

namespace Database\Factories;

use App\Models\PublicationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationTypeFactory extends Factory
{
    protected $model = PublicationType::class;

    public function definition(): array
    {
        return [
            'type_name' => $this->faker->unique()->words(3, true),
        ];
    }
}
