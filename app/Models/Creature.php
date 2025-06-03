<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creature extends Model
{
    public $timestamps = false;
    /** @use HasFactory<\Database\Factories\CreatureFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'size_id',
        'level',
        'hp',
        'ac',
        'fortitude',
        'reflex',
        'will',
        'perception',
        'senses',
        'speed',
        'rarity_id',
        'custom'
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function rarity()
    {
        return $this->belongsTo(Rarity::class);
    }

    public function pathfindertraits(){
        return $this->belongsToMany(PathfinderTrait::class);
    }
}
