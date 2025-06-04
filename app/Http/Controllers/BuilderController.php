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
        $creatures = null;
        $hazards = null;
        return view('builder.creature', compact([
            'creatures',
            'hazards',
        ]));
    }

    public function encounter () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $types = Type::get();
        return view('builder.encounter', compact([
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types'
        ]));
    }

    public function randomize () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $types = Type::get();
        return view('builder.randomize', compact([
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types'
        ]));
    }

    public function newcreature () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = null;
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        return view('builder.newcreature', compact([
            'creatures',
            'traits',
            'sizes',
            'rarities',
            'hazards'
        ]));
    }
}
