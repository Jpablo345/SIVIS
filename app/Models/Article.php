<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $table = 'article';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id',
        'journal_issn',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class, 'journal_issn', 'journal_issn');
    }
}
