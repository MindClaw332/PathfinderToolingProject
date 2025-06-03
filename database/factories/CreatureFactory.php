<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Creature>
 */
class CreatureFactory extends Factory
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
            'size_id' => fake()->randomElement(DB::table('sizes')->pluck('id')->toArray()),
            'level' => rand(-1,20),
            'hp' => rand(1,250),
            'ac' => rand(1,35),
            'fortitude' => rand(1,30),
            'reflex' => rand(1,30),
            'will' => rand(0,12),
            'perception' => rand(0,30),
            'senses' => fake()->randomElement(['darkvision, scent 30ft', 'darkvision', 'scent 50ft', 'sense undead, darkvision']),
            'speed' => fake()->randomElement(['25ft', '30ft', '45ft']),
            'rarity_id' => fake()->randomElement(DB::table('rarities')->pluck('id')->toArray()),
            'custom' => rand(0,1)
        ];
    }
}
