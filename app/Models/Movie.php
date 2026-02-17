<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'title',
        'release_date',
        'overview',
        'runtime',
        'poster_path',
    ];

    // 1 Movie n Generes
    public function genres() {
        return $this->belongsToMany(Genre::class);
    }

    // 1 Movie n Reviews
    public function reviews() {
        return $this->hasMany(Review::class);
    }

    // 1 Movie n Collections
    public function collections() {
        return $this->belongsToMany(Collection::class);
    }

    // 1 Movie n Users (para saber quién la tiene en su lista)
    public function users() {
        return $this->belongsToMany(User::class)->withPivot('status')->withTimestamps();
    }
}
