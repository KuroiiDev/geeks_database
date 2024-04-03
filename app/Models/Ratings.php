<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'user_id',
        'book_id',
        'review',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id', 'id');
    }
}
