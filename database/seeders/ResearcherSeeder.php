<?php

namespace Database\Seeders;

use App\Models\ResearchGroup;
use App\Models\Researcher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResearcherSeeder extends Seeder
{
    public function run(): void
    {
        $groupIds = ResearchGroup::pluck('cod_minciencias')->all();

        Researcher::factory(15)
            ->state(function () use ($groupIds) {
                return [
                    'cod_minciencias' => $groupIds ? Arr::random($groupIds) : null,
                ];
            })
            ->create();
    }
}
