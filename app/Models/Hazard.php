<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    /** @use HasFactory<\Database\Factories\HazardFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'complexity', 'type_id', 'rarity_id', 'source'];

    public function rarity(){
        return $this->belongsTo(Rarity::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }
}
