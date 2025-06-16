<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    public $timestamps = false;
    /**
     * @return HasMany<Creature,Size>
     */
    public function creatures(): HasMany
    {
        return $this->hasMany(Creature::class);
    }
}
