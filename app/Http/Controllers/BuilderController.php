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
        $contentId = 'creature';
        return view('builder.creature', compact([
            'contentId',
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
        $contentId = 'encounter';
        $availableCreatures = session("content_{$contentId}_creatures", []);
        return view('builder.encounter', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'availableCreatures'
        ]));
    }

    public function randomize () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $types = Type::get();
        $contentId = 'randomize';
        $availableCreatures = session("content_{$contentId}_creatures", []);
        return view('builder.randomize', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'availableCreatures'
        ]));
    }

    public function newcreature () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = null;
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $contentId = 'newcreature';
        return view('builder.newcreature', compact([
            'contentId',
            'creatures',
            'traits',
            'sizes',
            'rarities',
            'hazards'
        ]));
    }

    public function addCreature(Request $request, $contentId)
    {
        $request->validate([
            'creature_id' => 'required|exists:creatures,id'
        ]);
        
        $creature = Creature::find($request->creature_id);
        $sessionKey = "content_{$contentId}_creatures";
        $creatures = session($sessionKey, []);
        $creatures[] = $creature->toArray();
        session([$sessionKey => $creatures]);
        
        return response()->json([
            'success' => true,
            'creature' => $creature,
            'creatures' => $creatures
        ]);
    }
}
