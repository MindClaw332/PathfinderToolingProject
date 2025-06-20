<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    /** @use HasFactory<\Database\Factories\TypeFactory> */
    use HasFactory;
    /**
     * @return HasMany<Hazard,Type>
     */
    public function hazards(): HasMany{
        return $this->hasMany(Hazard::class);
    }
}
