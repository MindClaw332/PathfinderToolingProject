<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PathfinderTraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traits = [
            ['name' => 'demon'],
            ['name' => 'dragon'],
            ['name' => 'humanoid'],
        ];
        DB::table('pathfinder_traits')->insert($traits);
    }
}
