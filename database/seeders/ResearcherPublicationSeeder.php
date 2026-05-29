<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\Researcher;
use App\Models\ResearcherPublication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResearcherPublicationSeeder extends Seeder
{
    public function run(): void
    {
        $publicationIds = Publication::pluck('publication_id')->all();
        $researcherIds = Researcher::pluck('researcher_id')->all();

        if (empty($publicationIds) || empty($researcherIds)) {
            $this->command?->warn('Missing publications or researchers for researcher_publication.');
            return;
        }

        $maxPairs = count($publicationIds) * count($researcherIds);
        $target = min(15, $maxPairs);
        $used = [];
        $created = 0;
        $maxAttempts = $target * 5 + 10;

        for ($i = 0; $i < $maxAttempts && $created < $target; $i++) {
            $publicationId = Arr::random($publicationIds);
            $researcherId = Arr::random($researcherIds);
            $key = $publicationId . '|' . $researcherId;

            if (isset($used[$key])) {
                continue;
            }

            $used[$key] = true;

            ResearcherPublication::create([
                'publication_id' => $publicationId,
                'researcher_id' => $researcherId,
                'author_order' => random_int(1, 4),
            ]);

            $created++;
        }
    }
}
