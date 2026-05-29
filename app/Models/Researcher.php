<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Researcher extends Model
{
    use HasFactory;

    protected $table = 'researcher';
    protected $primaryKey = 'researcher_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'researcher_id',
        'name_1',
        'name_2',
        'last_name_1',
        'last_name_2',
        'email',
        'phone',
        'cod_minciencias',
    ];

    public function researchGroup(): BelongsTo
    {
        return $this->belongsTo(ResearchGroup::class, 'cod_minciencias', 'cod_minciencias');
    }

    public function publications(): BelongsToMany
    {
        return $this->belongsToMany(Publication::class, 'researcher_publication', 'researcher_id', 'publication_id')
            ->using(ResearcherPublication::class)
            ->withPivot('author_order');
    }

    public function researcherEvents(): HasMany
    {
        return $this->hasMany(ResearcherEvent::class, 'researcher_id', 'researcher_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'researcher_event', 'researcher_id', 'event_id')
            ->withPivot('event_part_id', 'presentation_title', 'participation_type');
    }
}
