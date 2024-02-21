<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $table = "books";
    protected $fillable = [
        'title',
        'writer',
        'publisher',
        'synopsis',
        'publish_year'
    ];
}
