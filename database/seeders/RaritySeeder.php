<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rarities = [
            ['name' => 'common'],
            ['name' => 'uncommon'],
            ['name' => 'rare'],
            ['name' => 'unique'],
        ];

        DB::table('rarities')->insert($rarities);
    }
}
