<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $typeIds = PublicationType::pluck('type_id')->all();

        Publication::factory(10)
            ->state(function () use ($typeIds) {
                return [
                    'type_id' => $typeIds ? Arr::random($typeIds) : null,
                ];
            })
            ->create();
    }
}
