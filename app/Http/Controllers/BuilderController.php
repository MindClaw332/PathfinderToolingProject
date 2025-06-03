<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creature;
use App\Models\Hazard;
use App\Models\Size;
use App\Models\Rarity;
use App\Models\PathfinderTrait;
use App\Models\Type;

class BuilderController extends Controller
{
    public function creature () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        return view('builder.creature', compact([
            'creatures',
            'hazards'
        ]));
    }

    public function encounter () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        return view('builder.encounter', compact([
            'creatures',
            'hazards'
        ]));
    }

    public function randomize () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        return view('builder.randomize', compact([
            'creatures',
            'hazards'
        ]));
    }

    public function newcreature () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        return view('builder.newcreature', compact([
            'creatures',
            'hazards'
        ]));
    }
}
