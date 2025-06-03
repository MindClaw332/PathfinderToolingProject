<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = ['name' => 'tiny', 'name' => 'small', 'name' => 'medium', 'name' => 'large', 'name' => 'huge', 'name' => 'gargantuan'];

        DB::table('sizes')->insert($sizes);
    }
}
