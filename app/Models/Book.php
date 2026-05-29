<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $table = 'book';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id',
        'book_isbn',
        'means_of_dissemination',
        'editorial',
        'book_type_id',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }

    public function bookType(): BelongsTo
    {
        return $this->belongsTo(BookType::class, 'book_type_id', 'book_type_id');
    }
}
