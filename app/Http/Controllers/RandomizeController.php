<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creature;
use App\Models\Hazard;
use App\Models\Size;
use App\Models\Rarity;
use App\Models\PathfinderTrait;
use App\Models\Type;

class RandomizeController extends Controller
{
    
    // Randomize an encounter
    public function randomizeEncounter(Request $request, $contentId) {
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

        // Base XP budget
        $baseXpBudgetMap = [
            'trivial' => 40,
            'low' => 60,
            'moderate' => 80,
            'severe' => 120,
            'extreme' => 160,
        ];

        // Character adjustment per threat level
        $xpAdjustmentMap = [
            'trivial' => 10,
            'low' => 20,
            'moderate' => 20,
            'severe' => 30,
            'extreme' => 40,
        ];

        $baseXpBudget = $baseXpBudgetMap[$threatLevel] ?? 80;
        $xpAdjustment = $xpAdjustmentMap[$threatLevel] ?? 20;

        // Adjust XP budget to party size
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
                    $xpUsed += $creatureXpMap[$diff];
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching chosen creatures', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Error processing chosen creatures'], 500);
            }
        }

        // Remaining XP for new creatures
        $remainingXp = $xpBudget - $xpUsed;

        // Check impossible cases
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

            // Filter and calculate XP for each creature
            $allCreatures = $allCreatures->filter(function ($creature) use ($partyLevel, $creatureXpMap) {
                $diff = $creature->level - $partyLevel;
                if ($diff < -4 || $diff > 4) {
                    return false;
                }
                $creature->calculated_xp = $creatureXpMap[$diff] ?? '';
                return true;
            });

            if ($allCreatures->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No creatures found matching the specified filters.',
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Error querying creatures', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching creatures'], 500);
        }

        // Try randomized selection fitting XP budget
        $newCreatures = [];
        $usedXp = 0;
        $shuffled = $allCreatures->shuffle();
        $attempts = 0;
        $maxAttempts = 100; // Prevent infinite loops

        // Improved algorithm: try multiple times with different approaches
        while (count($newCreatures) < $creatureAmount && $attempts < $maxAttempts) {
            $attempts++;
            
            foreach ($shuffled as $creature) {
                if (count($newCreatures) >= $creatureAmount) break;

                $potentialXp = $usedXp + $creature->calculated_xp;
                $remainingSlots = $creatureAmount - count($newCreatures);

                // For the last creature, we can be more flexible
                if ($remainingSlots == 1) {
                    if ($potentialXp <= $remainingXp) {
                        $newCreatures[] = $creature;
                        $usedXp += $creature->calculated_xp;
                        break;
                    }
                } else {
                    // Calculate minimum XP needed for remaining slots
                    $minNeededXp = ($remainingSlots - 1) * 10;

                    // Only pick this creature if we won't exceed XP budget and still have enough XP for remaining creatures
                    if ($potentialXp + $minNeededXp <= $remainingXp) {
                        $newCreatures[] = $creature;
                        $usedXp += $creature->calculated_xp;
                    }
                }
            }

            // If we didn't get enough creatures, try again with shuffled order
            if (count($newCreatures) < $creatureAmount) {
                $shuffled = $allCreatures->shuffle();
                $newCreatures = [];
                $usedXp = 0;
            }
        }

        // If after all attempts we didn't get enough creatures, fail gracefully
        if (count($newCreatures) < $creatureAmount) {
            return response()->json([
                'success' => false,
                'message' => "Unable to find enough creatures matching filters and XP constraints. Found {$newCreatures->count()} out of {$creatureAmount} requested creatures.",
            ], 400);
        }

        // Query hazards by type filter only, exclude chosen hazards
        $newHazards = collect();
        if ($hazardAmount > 0) {
            try {
                $query = Hazard::query()
                ->when($selectedType, function ($q) use ($selectedType) {
                    $q->where('type_id', $selectedType);
                });

                $newHazards = $query->inRandomOrder()->limit($hazardAmount)->get();
            } catch (\Exception $e) {
                \Log::error('Error querying hazards', ['error' => $e->getMessage()]);
                // Don't fail the entire request for hazards, just return empty collection
            }
        }

        $finalXpUsed = $xpUsed + $usedXp;
        $finalXpRemaining = $xpBudget - $finalXpUsed;

        \Log::info('Randomize encounter completed successfully', [
            'creatures_found' => count($newCreatures),
            'hazards_found' => $newHazards->count(),
            'xp_budget' => $xpBudget,
            'xp_used' => $finalXpUsed,
        ]);

        $creatureHTML = view('builder.partials.newCreature', ['newCreatures' => $newCreatures])->render();
        $hazardHTML = view('builder.partials.newHazard', ['newHazards' => $newHazards])->render();

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
                'average_xp_per_creature' => $averageXpPerCreature,
                'total_creatures_available' => $allCreatures->count(),
            ]
        ]);
    }

    private function randomThreatLevel()
    {
        $levels = ['trivial', 'low', 'moderate', 'severe', 'extreme'];
        return $levels[array_rand($levels)];
    }
}
