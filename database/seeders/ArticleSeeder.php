<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $journalIssns = Journal::pluck('journal_issn')->all();

        if (empty($journalIssns)) {
            $this->command?->warn('No journals found. Seed journals before articles.');
            return;
        }

        $typeIds = PublicationType::pluck('type_id')->all();

        foreach (range(1, 10) as $i) {
            $publication = Publication::factory()->create([
                'type_id' => $typeIds ? Arr::random($typeIds) : null,
            ]);

            Article::create([
                'publication_id' => $publication->publication_id,
                'journal_issn' => Arr::random($journalIssns),
            ]);
        }
    }
}
