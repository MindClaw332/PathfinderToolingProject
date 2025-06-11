<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
    /**
     * @return BelongsTo
     */
    public function size():BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    /**
    * @return BelongsTo
    */

    public function rarity():BelongsTo
    {
        return $this->belongsTo(Rarity::class);
    }
    /**
     * @return BelongsToMany<PathfinderTrait,Creature,Pivot>
     */
    public function pathfinderTraits():BelongsToMany
    {
        return $this->belongsToMany(PathfinderTrait::class);
    }
}
