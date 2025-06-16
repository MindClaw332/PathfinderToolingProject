<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combatant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ac',
        'max_hp',
        'current_hp',
        'initiative',
        'conditions',
        'creature_id',
        'encounter_id',
        'initiative_bonus',
        'speed',
        'perception',
        'fort_save',
        'ref_save',
        'will_save',
        'actions',
        'type'
    ];

    public function creature()
    {
        return $this->belongsTo(Creature::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
}
