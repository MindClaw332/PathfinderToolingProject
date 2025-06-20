<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creature;
use App\Models\Hazard;
use App\Models\Size;
use App\Models\Rarity;
use App\Models\PathfinderTrait;
use App\Models\Type;

use Illuminate\Support\Facades\Log;

class RandomizeController extends Controller
{
    /**
     * Randomize an encounter with optimal XP usage and maximum variation
     */
    public function randomizeEncounter(Request $request, $contentId)
    {
        $partyLevel = $request->input('partyLevel');
        $partySize = $request->input('partySize');

        // Optional inputs with defaults
        $threatLevel = strtolower($request->input('threatLevel') ?? $this->randomThreatLevel());
        $creatureAmount = $request->input('creatureAmount') ?? rand(1, 5);
        $hazardAmount = $request->input('hazardAmount') ?? rand(0, 2);
        $chosenCreatures = $request->input('chosenCreatures', []);
        $chosenHazards = $request->input('chosenHazards', []);
        $selectedTrait = $request->input('selectedTrait');
        $selectedType = $request->input('selectedType');
        $selectedSizes = $request->input('selectedSizes', []);

        // Base XP budget calculation
        $baseXpBudgetMap = [
            'trivial' => 40,
            'low' => 60,
            'moderate' => 80,
            'severe' => 120,
            'extreme' => 160,
        ];

        $xpAdjustmentMap = [
            'trivial' => 10,
            'low' => 20,
            'moderate' => 20,
            'severe' => 30,
            'extreme' => 40,
        ];

        $baseXpBudget = $baseXpBudgetMap[$threatLevel] ?? 80;
        $xpAdjustment = $xpAdjustmentMap[$threatLevel] ?? 20;

        // Adjust XP budget for party size
        if ($partySize < 4) {
            $baseXpBudget -= $xpAdjustment * (4 - $partySize);
        } elseif ($partySize > 5) {
            $baseXpBudget += $xpAdjustment * ($partySize - 5);
        }
        $xpBudget = max($baseXpBudget, 0);

        // Creature XP map based on level difference
        $creatureXpMap = [
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

        // Calculate XP used by already chosen creatures
        $xpUsed = 0;
        if (!empty($chosenCreatures)) {
            try {
                foreach ($chosenCreatures as $creature) {
                    $diff = $creature['level'] - $partyLevel;
                    $xpUsed += $creatureXpMap[$diff] ?? 40;
                }
            } catch (\Exception $e) {
                Log::error('Error processing chosen creatures', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Error processing chosen creatures'], 500);
            }
        }

        $remainingXp = $xpBudget - $xpUsed;

        // Validation checks
        if ($remainingXp <= 0) {
            return response()->json(['success' => false, 'message' => 'No XP budget left for new creatures'], 400);
        }

        $averageXpPerCreature = $remainingXp / $creatureAmount;
        if ($averageXpPerCreature < 10) {
            return response()->json([
                'success' => false,
                'message' => "Impossible to create {$creatureAmount} creatures within remaining XP budget. Average XP per creature ({$averageXpPerCreature}) is below minimum 10.",
            ], 400);
        }

        // Fetch and filter creatures
        try {
            $query = Creature::query()
                ->when(!empty($selectedSizes), function ($q) use ($selectedSizes) {
                    $q->whereIn('size_id', $selectedSizes);
                })
                ->when(!empty($selectedTrait), function ($q) use ($selectedTrait) {
                    $q->whereHas('pathfinderTraits', function ($q2) use ($selectedTrait) {
                        $q2->where('pathfinder_trait_id', $selectedTrait);
                    });
                });

            $allCreatures = $query->get();

            // Filter by level range (-4 to +4) and calculate XP
            $viableCreatures = $allCreatures->filter(function ($creature) use ($partyLevel, $creatureXpMap) {
                $levelDiff = $creature->level - $partyLevel;
                
                // Only include creatures within valid level range
                if ($levelDiff < -4 || $levelDiff > 4) {
                    return false;
                }
                
                $creature->calculated_xp = $creatureXpMap[$levelDiff];
                return true;
            });

            if ($viableCreatures->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No viable creatures found matching the specified filters and level requirements.',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error querying creatures', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching creatures'], 500);
        }

        // Use optimal XP randomization algorithm
        $selectionResult = $this->optimizedCreatureSelection($viableCreatures, $remainingXp, $creatureAmount);
        
        if (count($selectionResult['creatures']) < $creatureAmount) {
            return response()->json([
                'success' => false,
                'message' => "Unable to find {$creatureAmount} creatures that fit the XP budget efficiently. Found {$selectionResult['count']} creatures using {$selectionResult['xpUsed']} XP.",
            ], 400);
        }

        // Fetch hazards
        $newHazards = collect();
        if ($hazardAmount > 0) {
            try {
                $query = Hazard::query()
                    ->when($selectedType, function ($q) use ($selectedType) {
                        $q->where('type_id', $selectedType);
                    });

                $newHazards = $query->inRandomOrder()->limit($hazardAmount)->get();
            } catch (\Exception $e) {
                Log::error('Error querying hazards', ['error' => $e->getMessage()]);
            }
        }

        $finalXpUsed = $xpUsed + $selectionResult['xpUsed'];
        $finalXpRemaining = $xpBudget - $finalXpUsed;
        $xpEfficiency = $remainingXp > 0 ? ($selectionResult['xpUsed'] / $remainingXp) * 100 : 0;

        // Assign selected creatures to $newCreatures for consistency
        $newCreatures = $selectionResult['creatures'];

        // Generate HTML views for creatures and hazards
        $creatureHTML = view('builder.partials.newCreature', ['newCreatures' => $newCreatures])->render();
        $hazardHTML = view('builder.partials.newHazard', ['newHazards' => $newHazards])->render();

        Log::info('Randomize encounter completed successfully', [
            'creatures_found' => count($newCreatures),
            'hazards_found' => $newHazards->count(),
            'xp_efficiency' => round($xpEfficiency, 1) . '%',
            'xp_budget' => $xpBudget,
            'xp_used' => $finalXpUsed,
        ]);

        return response()->json([
            'success' => true,
            'newCreatures' => $newCreatures,
            'newHazards' => $newHazards,
            'xpBudget' => $xpBudget,
            'xpUsed' => $finalXpUsed,
            'xpRemaining' => $finalXpRemaining,
            'creatureHTML' => $creatureHTML,
            'hazardHTML' => $hazardHTML,
            'debug' => [
                'threat_level' => $threatLevel,
                'remaining_xp_for_selection' => $remainingXp,
                'average_xp_per_creature' => round($averageXpPerCreature, 1),
                'total_creatures_available' => $allCreatures->count(),
                'xp_efficiency' => round($xpEfficiency, 1) . '%',
            ]
        ]);
    }

    /**
     * Optimized creature selection for maximum XP usage with high variation
     */
    private function optimizedCreatureSelection($viableCreatures, $remainingXp, $creatureAmount)
    {
        $creatureCount = $viableCreatures->count();

        // Choose algorithm based on available creatures
        if ($creatureCount >= 1000) {
            return $this->ultraHighEfficiencySelection($viableCreatures, $remainingXp, $creatureAmount);
        } elseif ($creatureCount >= 100) {
            return $this->maxVariationSelection($viableCreatures, $remainingXp, $creatureAmount);
        } else {
            return $this->adaptiveSelection($viableCreatures, $remainingXp, $creatureAmount);
        }
    }

    /**
     * Ultra-high efficiency selection for large creature pools (95%+ XP usage)
     */
    private function ultraHighEfficiencySelection($viableCreatures, $remainingXp, $creatureAmount)
    {
        $perfectCombinations = [];
        $nearPerfectCombinations = [];
        $maxAttempts = 300;

        for ($attempt = 0; $attempt < $maxAttempts && count($perfectCombinations) < 30; $attempt++) {
            $combination = [];
            $usedXp = 0;
            $shuffled = $viableCreatures->shuffle();

            foreach ($shuffled as $creature) {
                if (count($combination) >= $creatureAmount) break;

                $newXp = $usedXp + $creature->calculated_xp;
                $slotsLeft = $creatureAmount - count($combination);
                $minNeeded = ($slotsLeft - 1) * 10;

                if ($newXp + $minNeeded <= $remainingXp) {
                    $combination[] = $creature;
                    $usedXp = $newXp;
                }
            }

            if (count($combination) == $creatureAmount) {
                $efficiency = $usedXp / $remainingXp;
                $signature = collect($combination)->pluck('id')->sort()->implode('-');

                if ($efficiency >= 0.95 && !isset($perfectCombinations[$signature])) {
                    $perfectCombinations[$signature] = [
                        'creatures' => $combination,
                        'xpUsed' => $usedXp,
                        'efficiency' => $efficiency
                    ];
                } elseif ($efficiency >= 0.9 && !isset($nearPerfectCombinations[$signature])) {
                    $nearPerfectCombinations[$signature] = [
                        'creatures' => $combination,
                        'xpUsed' => $usedXp,
                        'efficiency' => $efficiency
                    ];
                }
            }
        }

        $combinationsToUse = !empty($perfectCombinations) ? $perfectCombinations : $nearPerfectCombinations;

        if (empty($combinationsToUse)) {
            return ['creatures' => [], 'xpUsed' => 0, 'count' => 0];
        }

        $selected = collect($combinationsToUse)->random();
        return [
            'creatures' => $selected['creatures'],
            'xpUsed' => $selected['xpUsed'],
            'count' => count($selected['creatures'])
        ];
    }

    /**
     * Maximum variation selection for medium creature pools (90%+ XP usage)
     */
    private function maxVariationSelection($viableCreatures, $remainingXp, $creatureAmount)
    {
        $viableCombinations = [];
        $maxAttempts = 250;

        for ($attempt = 0; $attempt < $maxAttempts && count($viableCombinations) < 40; $attempt++) {
            // Use different randomization strategies for variety
            $strategy = $attempt % 4;
            switch ($strategy) {
                case 0: // Pure random
                    $candidates = $viableCreatures->shuffle();
                    break;
                case 1: // High XP bias
                    $candidates = $viableCreatures->sortByDesc('calculated_xp')
                        ->take(ceil($viableCreatures->count() * 0.7))->shuffle();
                    break;
                case 2: // Mixed XP levels
                    $high = $viableCreatures->sortByDesc('calculated_xp')->take(ceil($viableCreatures->count() * 0.4));
                    $low = $viableCreatures->sortBy('calculated_xp')->take(ceil($viableCreatures->count() * 0.4));
                    $candidates = $high->merge($low)->shuffle();
                    break;
                default: // Balanced approach
                    $candidates = $viableCreatures->shuffle();
                    break;
            }

            $combination = [];
            $usedXp = 0;

            foreach ($candidates as $creature) {
                if (count($combination) >= $creatureAmount) break;

                $newXp = $usedXp + $creature->calculated_xp;
                $slotsLeft = $creatureAmount - count($combination);
                $minNeeded = ($slotsLeft - 1) * 10;

                if ($newXp + $minNeeded <= $remainingXp) {
                    $combination[] = $creature;
                    $usedXp = $newXp;
                }
            }

            if (count($combination) == $creatureAmount && $usedXp >= $remainingXp * 0.9) {
                $signature = collect($combination)->pluck('id')->sort()->implode('-');

                if (!isset($viableCombinations[$signature])) {
                    $viableCombinations[$signature] = [
                        'creatures' => $combination,
                        'xpUsed' => $usedXp,
                        'efficiency' => $usedXp / $remainingXp
                    ];
                }
            }
        }

        if (empty($viableCombinations)) {
            return ['creatures' => [], 'xpUsed' => 0, 'count' => 0];
        }

        $selected = collect($viableCombinations)->random();
        return [
            'creatures' => $selected['creatures'],
            'xpUsed' => $selected['xpUsed'],
            'count' => count($selected['creatures'])
        ];
    }

    /**
     * Adaptive selection for smaller creature pools (80%+ XP usage)
     */
    private function adaptiveSelection($viableCreatures, $remainingXp, $creatureAmount)
    {
        $bestCombination = [];
        $bestXpUsed = 0;
        $maxAttempts = 200;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $combination = [];
            $usedXp = 0;
            $shuffled = $viableCreatures->shuffle();

            foreach ($shuffled as $creature) {
                if (count($combination) >= $creatureAmount) break;

                $newXp = $usedXp + $creature->calculated_xp;
                $slotsLeft = $creatureAmount - count($combination);
                $minNeeded = ($slotsLeft - 1) * 10;

                if ($newXp + $minNeeded <= $remainingXp) {
                    $combination[] = $creature;
                    $usedXp = $newXp;
                }
            }

            // Accept if we have the right count and decent XP usage
            if (count($combination) == $creatureAmount && $usedXp >= $remainingXp * 0.8) {
                if ($usedXp > $bestXpUsed) {
                    $bestCombination = $combination;
                    $bestXpUsed = $usedXp;
                }

                // If we hit 95%+ efficiency, that's excellent
                if ($usedXp >= $remainingXp * 0.95) {
                    break;
                }
            }
        }

        return [
            'creatures' => $bestCombination,
            'xpUsed' => $bestXpUsed,
            'count' => count($bestCombination)
        ];
    }

    /**
     * Generate a random threat level
     */
    private function randomThreatLevel()
    {
        $levels = ['trivial', 'low', 'moderate', 'severe', 'extreme'];
        return $levels[array_rand($levels)];
    }
}
