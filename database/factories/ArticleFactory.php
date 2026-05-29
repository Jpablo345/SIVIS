<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Journal;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'journal_issn' => Journal::factory(),
        ];
    }
}
