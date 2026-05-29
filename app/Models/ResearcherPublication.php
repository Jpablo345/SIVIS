<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ResearcherPublication extends Pivot
{
    use HasFactory;

    protected $table = 'researcher_publication';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id',
        'researcher_id',
        'author_order',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }

    public function researcher(): BelongsTo
    {
        return $this->belongsTo(Researcher::class, 'researcher_id', 'researcher_id');
    }
}
