<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $cats = [
        'free'  => 'boolean',
    ];
    // public function comments()
    // {
    //     return $this->morphMany(Commont::class, 'commentable');
    // }
}
