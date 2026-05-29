<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $institutionIds = Institution::pluck('institution_id')->all();

        Event::factory(10)
            ->state(function () use ($institutionIds) {
                return [
                    'host_institution_id' => $institutionIds ? Arr::random($institutionIds) : null,
                    'origin_institution_id' => $institutionIds ? Arr::random($institutionIds) : null,
                ];
            })
            ->create();
    }
}
