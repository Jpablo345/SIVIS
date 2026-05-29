<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Researcher;
use App\Models\ResearcherEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearcherEventFactory extends Factory
{
    protected $model = ResearcherEvent::class;

    public function definition(): array
    {
        return [
            'presentation_title' => $this->faker->sentence(4),
            'participation_type' => $this->faker->randomElement(['Ponente', 'Asistente', 'Organizador']),
            'event_id' => Event::factory(),
            'researcher_id' => Researcher::factory(),
        ];
    }
}
