<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\ResearchGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResearchGroupSeeder extends Seeder
{
    public function run(): void
    {
        $institutionIds = Institution::pluck('institution_id')->all();

        ResearchGroup::factory(5)
            ->state(function () use ($institutionIds) {
                return [
                    'institution_id' => $institutionIds ? Arr::random($institutionIds) : null,
                ];
            })
            ->create();
    }
}
