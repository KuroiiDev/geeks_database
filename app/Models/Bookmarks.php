<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmarks extends Model
{
    protected $table = "bookmarks";

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Books::class, 'book_id');
    }
}
