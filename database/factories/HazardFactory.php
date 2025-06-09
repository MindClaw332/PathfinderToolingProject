<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hazard>
 */
class HazardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'complexity' => rand(0, 1),
            'rarity_id' => fake()->randomElement(DB::table('rarities')->pluck('id')->toArray()),
            'source' => fake()->text(),
            'type_id' => fake()->randomElement(DB::table('types')->pluck('id')->toArray())
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($hazard) {

            for ($i = 0; $i <= rand(1, 3); $i++) {
                $pivotInsert = [
                    'hazard_id' => $hazard->id,
                    'pathfinder_trait_id' => fake()->randomElement(DB::table('pathfinder_traits')->pluck('id')->toArray())
                ];
                DB::table('hazard_pathfinder_trait')->insert($pivotInsert);
            }
        });
    }
}
