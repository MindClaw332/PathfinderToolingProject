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
        $threatLevel = null;
        $skippedCreatures = null;
        $newCreatures = [];
        $newHazards = [];
        return view('builder.encounter', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'chosenCreatures',
            'chosenHazards',
            'threatLevel',
            'skippedCreatures',
            'newCreatures',
            'newHazards',
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
        $threatLevel = null;
        $skippedCreatures = null;
        $newCreatures = [];
        $newHazards = [];
        $hazardCount = count($chosenHazards);
        return view('builder.randomize', compact([
            'contentId',
            'creatures',
            'hazards',
            'traits',
            'sizes',
            'rarities',
            'types',
            'chosenCreatures',
            'chosenHazards',
            'threatLevel',
            'skippedCreatures',
            'newCreatures',
            'newHazards',
            'hazardCount',
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
            'hazards',
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

        $newCreatures = [];
        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures, 'newCreatures' => $newCreatures])->render();
        
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
            // Store original level if it doesn't exist yet
            if (!isset($creatures[$index]['original_level'])) {
                $creatures[$index]['original_level'] = $creatures[$index]['level'];
            }
            
            $creatures[$index] = array_merge(
                $creatures[$index], 
                $request->only([
                    'level',
                    'hp',
                    'ac',
                    'fortitude',
                    'reflex',
                    'will',
                    'perception',
                ])
            );
            session([$sessionKey => $creatures]);
        }

        $newCreatures = [];
        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures, 'newCreatures' => $newCreatures])->render();
        
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

        $newCreatures = [];
        $html = view('builder.partials.creatureList', ['chosenCreatures' => $creatures, 'newCreatures' => $newCreatures])->render();

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

        $newHazards = [];
        $html = view('builder.partials.hazardList', ['chosenHazards' => $hazards, 'newHazards' => $newHazards])->render();
        $hazardCount = count($hazards);
        
        return response()->json([
            'success' => true,
            'hazard' => $hazard,
            'html' => $html,
            'hazardCount' => $hazardCount,
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

        $newHazards = [];
        $html = view('builder.partials.hazardList', ['chosenHazards' => $hazards, 'newHazards' => $newHazards])->render();
        $hazardCount = count($hazards);
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'hazardCount' => $hazardCount,
        ]);
    }

    // Calculate encounter XP 
    public function calculateXP (Request $request, $contentId) {
        // Get the selected creatures
        $sessionKey = "content_{$contentId}_creatures";
        $creatures = session($sessionKey, []);

        $partyLevel = (int) $request->input('party_level');
        $partySize  = (int) $request->input('party_size');

        $totalXP = 0;
        $skippedCreatures = [];

        // Creature XP table
        $xpTable = [
            -4 => 10,
            -3 => 15,
            -2 => 20,
            -1 => 30,
            0 => 40,
            1 => 60,
            2 => 80,
            3 => 120,
            4 => 160,
        ];

        // Base threat XP budgets for party size 4 or 5
        $baseBudgets = [
            'Trivial' => 40,
            'Low' => 60,
            'Moderate' => 80,
            'Severe' => 120,
            'Extreme' => 160,
        ];

        // Adjustment XP per character if party size is smaller or larger than 4-5
        $adjustments = [
            'Trivial' => 10,
            'Low' => 20,
            'Moderate' => 20,
            'Severe' => 30,
            'Extreme' => 40,
        ];

        // Calculate XP of all creatures
        foreach ($creatures as $creature) {
            $creatureLevel = (int) $creature['level'];
            $diff = $creatureLevel - $partyLevel;

            if ($diff >= -4 && $diff <= 4) {
                $xpPerCreature = $xpTable[$diff] ?? 0;
                $totalXP += $xpPerCreature;
            } else {
                // save info about skipped creature
                $skippedCreatures[] = [
                    'name' => $creature['name'],
                    'level' => $creatureLevel,
                ];
            }
        }

        // Calculate base budgets based on party size
        if ($partySize >= 4 && $partySize <= 5) {
            $finalBudgets = $baseBudgets;
        } else {
            // Calculate difference from the standard range (4-5)
            $diff = $partySize < 4 ? $partySize - 4 : $partySize - 5;
            $finalBudgets = [];
            foreach ($baseBudgets as $level => $base) {
                $finalBudgets[$level] = max(0, $base + ($adjustments[$level] * $diff));
            }
        }

        // Determine threat level
        $threatLevel = 'None';
        if ($totalXP > 0) {
            if ($totalXP > $finalBudgets['Extreme']) {
                $threatLevel = 'Over Extreme';
            } elseif ($totalXP > $finalBudgets['Severe']) {
                $threatLevel = 'Extreme';
            } elseif ($totalXP > $finalBudgets['Moderate']) {
                $threatLevel = 'Severe';
            } elseif ($totalXP > $finalBudgets['Low']) {
                $threatLevel = 'Moderate';
            } elseif ($totalXP > $finalBudgets['Trivial']) {
                $threatLevel = 'Low';
            } else {
                $threatLevel = 'Trivial';
            }
        }

        $html = view('builder.partials.encounterBudget', ['threatLevel' => $threatLevel, 'skippedCreatures' => $skippedCreatures])->render();
        $select = view('builder.partials.selectThreat', ['threatLevel' => $threatLevel])->render();
        $randomize = view('builder.partials.randomize', ['skippedCreatures' => $skippedCreatures])->render();
        $creatureCount = count($creatures) - count($skippedCreatures);

        return response()->json([
            'success' => true,
            'total_xp' => $totalXP,
            'threat_level' => $threatLevel,
            'skippedCreatures' => $skippedCreatures,
            'html' => $html,
            'select' => $select,
            'randomize' => $randomize,
            'creatureCount' => $creatureCount,
        ]);
    }
}
