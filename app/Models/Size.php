<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public $timestamps = false;

    public function creatures()
    {
        return $this->hasMany(Creature::class);
    }
}
