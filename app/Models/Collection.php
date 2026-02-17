<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_public',
        'user_id',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movies() {
        return $this->belongsToMany(Movie::class, 'collection_movie');
    }
}
