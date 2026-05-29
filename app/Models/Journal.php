<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journal';
    protected $primaryKey = 'journal_issn';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'journal_issn',
        'journal_name',
        'category',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'journal_issn', 'journal_issn');
    }
}
