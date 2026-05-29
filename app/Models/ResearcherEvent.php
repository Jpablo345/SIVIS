<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearcherEvent extends Model
{
    use HasFactory;

    protected $table = 'researcher_event';
    protected $primaryKey = 'event_part_id';
    public $timestamps = false;

    protected $fillable = [
        'presentation_title',
        'participation_type',
        'event_id',
        'researcher_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function researcher(): BelongsTo
    {
        return $this->belongsTo(Researcher::class, 'researcher_id', 'researcher_id');
    }
}
