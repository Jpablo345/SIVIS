<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicationType extends Model
{
    use HasFactory;

    protected $table = 'publication_type';
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    protected $fillable = [
        'type_name',
    ];

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'type_id', 'type_id');
    }
}
