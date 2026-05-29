<?php

namespace Database\Seeders;

use App\Models\AudiResearcher;
use Illuminate\Database\Seeder;

class AudiResearcherSeeder extends Seeder
{
    public function run(): void
    {
        AudiResearcher::factory(10)->create();
    }
}
