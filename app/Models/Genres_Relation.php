<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genres_Relation extends Model
{
    protected $table = 'genres_relations';

    protected $fillable = [
        'genre_id',
        'book_id',
    ];

    public function genre()
    {
        return $this->belongsTo(Genres::class, 'genre_id', 'id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id', 'id');
    }
}
