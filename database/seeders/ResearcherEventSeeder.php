<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Researcher;
use App\Models\ResearcherEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResearcherEventSeeder extends Seeder
{
    public function run(): void
    {
        $eventIds = Event::pluck('event_id')->all();
        $researcherIds = Researcher::pluck('researcher_id')->all();

        if (empty($eventIds) || empty($researcherIds)) {
            $this->command?->warn('Missing events or researchers for researcher_event.');
            return;
        }

        $maxPairs = count($eventIds) * count($researcherIds);
        $target = min(15, $maxPairs);
        $used = [];
        $created = 0;
        $maxAttempts = $target * 5 + 10;

        for ($i = 0; $i < $maxAttempts && $created < $target; $i++) {
            $eventId = Arr::random($eventIds);
            $researcherId = Arr::random($researcherIds);
            $key = $eventId . '|' . $researcherId;

            if (isset($used[$key])) {
                continue;
            }

            $used[$key] = true;

            ResearcherEvent::create([
                'event_id' => $eventId,
                'researcher_id' => $researcherId,
                'presentation_title' => fake()->sentence(4),
                'participation_type' => Arr::random(['Ponente', 'Asistente', 'Organizador']),
            ]);

            $created++;
        }
    }
}
