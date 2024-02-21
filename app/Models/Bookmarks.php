<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmarks extends Model
{
    protected $table = "bookmarks";

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id');
    }
}
