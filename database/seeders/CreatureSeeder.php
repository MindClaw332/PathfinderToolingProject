<?php

namespace Database\Seeders;

use App\Models\Creature;
use Illuminate\Database\Seeder;

class CreatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Creature::factory(50)->create();
        Creature::factory(50)->create();
    }
}
