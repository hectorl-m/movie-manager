<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'rating',
        'content',
        'user_id',
        'movie_id',
        'is_visible',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}
