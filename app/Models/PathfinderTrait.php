<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PathfinderTrait extends Model
{
    protected $table = 'pathfinder_traits';
    public $timestamps = false;
    /**
     * @return BelongsToMany<Creature,PathfinderTrait,Pivot>
     */
    public function creatures(): BelongsToMany{
        return $this->belongsToMany(Creature::class);
    }
    /**
     * @return BelongsToMany<Hazard,PathfinderTrait,Pivot>
     */
    public function hazards(): BelongsToMany{
        return $this->belongsToMany(Hazard::class);
    }

}
