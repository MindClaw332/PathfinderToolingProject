<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Hazard extends Model
{
    /** @use HasFactory<\Database\Factories\HazardFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'complexity', 'type_id', 'rarity_id', 'source'];
    /**
     * @return BelongsTo<Rarity,Hazard>
     */
    public function rarity(): BelongsTo{
        return $this->belongsTo(Rarity::class);
    }
    /**
     * @return BelongsTo<Type,Hazard>
     */
    public function type(): BelongsTo{
        return $this->belongsTo(Type::class);
    }
    /**
     * @return BelongsToMany<PathfinderTrait,Hazard,Pivot>
     */
    public function pathfindertraits(): BelongsToMany{
        return $this->belongsToMany(PathfinderTrait::class);
    }
}
