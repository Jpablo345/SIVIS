<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'event_name' => $this->faker->sentence(4),
            'event_year' => (string) $this->faker->numberBetween(2000, 2026),
            'event_month' => $this->faker->monthName(),
            'event_scope' => $this->faker->randomElement(['Nacional', 'Internacional']),
            'event_url' => $this->faker->optional()->url(),
            'host_institution_id' => null,
            'origin_institution_id' => null,
        ];
    }
}
