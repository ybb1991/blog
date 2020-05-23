<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commont extends Model
{
    public function commentable()
    {
        return $this->morphTo();
    }
}
