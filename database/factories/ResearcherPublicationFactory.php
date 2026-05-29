<?php

namespace Database\Factories;

use App\Models\Publication;
use App\Models\Researcher;
use App\Models\ResearcherPublication;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearcherPublicationFactory extends Factory
{
    protected $model = ResearcherPublication::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'researcher_id' => Researcher::factory(),
            'author_order' => $this->faker->numberBetween(1, 4),
        ];
    }
}
