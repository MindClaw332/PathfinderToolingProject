<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rarity extends Model
{
    /** @use HasFactory<\Database\Factories\RarityFactory> */
    use HasFactory;
    public $timestamps = false;


    public function creatures()
    {
        return $this->hasMany(Creature::class);
    }

    public function hazards(){
        return $this->hasMany(Hazard::class);
    }
}
