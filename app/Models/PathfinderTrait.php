<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathfinderTrait extends Model
{
    protected $table = 'pathfinder_traits';
    public $timestamps = false;

    public function creatures(){
        return $this->belongsToMany(Creature::class);
    }

    public function hazards(){
        return $this->belongsToMany(Hazard::class);
    }

}
