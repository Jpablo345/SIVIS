<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    public function run(): void
    {
        Journal::factory(10)->create();
    }
}
