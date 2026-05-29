<?php

namespace Database\Seeders;

use App\Models\AudiPublication;
use Illuminate\Database\Seeder;

class AudiPublicationSeeder extends Seeder
{
    public function run(): void
    {
        AudiPublication::factory(10)->create();
    }
}
