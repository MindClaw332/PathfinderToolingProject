<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rarity extends Model
{
    /** @use HasFactory<\Database\Factories\RarityFactory> */
    use HasFactory;
    public $timestamps = false;
    /**
     * @return HasMany<Creature,Rarity>
     */
    public function creatures(): HasMany
    {
        return $this->hasMany(Creature::class);
    }
    /**
     * @return HasMany<Hazard,Rarity>
     */
    public function hazards(): HasMany{
        return $this->hasMany(Hazard::class);
    }
}
