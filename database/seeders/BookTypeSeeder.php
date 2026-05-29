<?php

namespace Database\Seeders;

use App\Models\BookType;
use Illuminate\Database\Seeder;

class BookTypeSeeder extends Seeder
{
    public function run(): void
    {
        BookType::factory(5)->create();
    }
}
