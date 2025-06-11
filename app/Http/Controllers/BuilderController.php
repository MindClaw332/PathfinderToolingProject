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
    // Get My Creatures content
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

    // Get encounter content
    public function encounter () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $types = Type::get();
        $contentId = 'encounter';
        $chosenCreatures = session("content_{$contentId}_creatures", []);
        $chosenHazards = session("content_{$contentId}_hazards", []);
        return view('builder.encounter', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'chosenCreatures',
            'chosenHazards'
        ]));
    }

    // Get randomize content
    public function randomize () {
        $creatures = Creature::with('size', 'rarity', 'pathfindertraits')->get();
        $hazards = Hazard::with('type', 'rarity', 'pathfindertraits')->get();
        $traits = PathfinderTrait::get();
        $sizes = Size::get();
        $rarities = Rarity::get();
        $types = Type::get();
        $contentId = 'randomize';
        $chosenCreatures = session("content_{$contentId}_creatures", []);
        $chosenHazards = session("content_{$contentId}_hazards", []);
        return view('builder.randomize', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'chosenCreatures',
            'chosenHazards'
        ]));
    }

    // Get Create New Creature content
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

    // Add creature to the correct content array
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

        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures])->render();
        
        return response()->json([
            'success' => true,
            'creature' => $creature,
            'html' => $html,
        ]);
    }

    // Update creature level
    public function updateCreature(Request $request, $contentId, $index)
    {
        $sessionKey = "content_{$contentId}_creatures";
        $creatures = session($sessionKey, []);
        
        if (isset($creatures[$index])) {
            $creatures[$index] = array_merge(
                $creatures[$index], 
                $request->only(['level'])
            );
            session([$sessionKey => $creatures]);
        }

        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures])->render();
        
        return response()->json([
            'success' => true,
            'creature' => $creatures[$index] ?? null,
            'html' => $html,
        ]);
    }

    // Remove creature from the correct content array
    public function removeCreature($contentId, $index)
    {
        $sessionKey = "content_{$contentId}_creatures";
        $creatures = session($sessionKey, []);
        
        if (isset($creatures[$index])) {
            unset($creatures[$index]);
            $creatures = array_values($creatures);
            session([$sessionKey => $creatures]);
        }

        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }

    // Add hazard to the correct content array
    public function addHazard(Request $request, $contentId)
    {
        $request->validate([
            'hazard_id' => 'required|exists:hazards,id'
        ]);
        
        $hazard = Hazard::find($request->hazard_id);
        $sessionKey = "content_{$contentId}_hazards";
        $hazards = session($sessionKey, []);
        $hazards[] = $hazard->toArray();
        session([$sessionKey => $hazards]);

        $html = view('builder.partials.hazardList', ['chosenHazards' => $hazards])->render();
        
        return response()->json([
            'success' => true,
            'hazard' => $hazard,
            'html' => $html,
        ]);
    }

    // Remove hazard from the correct content array
    public function removeHazard($contentId, $index)
    {
        $sessionKey = "content_{$contentId}_hazards";
        $hazards = session($sessionKey, []);
        
        if (isset($hazards[$index])) {
            unset($hazards[$index]);
            $hazards = array_values($hazards);
            session([$sessionKey => $hazards]);
        }

        $html = view('builder.partials.hazardList', ['chosenHazards' => $hazards])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}
