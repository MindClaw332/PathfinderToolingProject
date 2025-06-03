<?php

namespace Database\Seeders;

use App\Models\Hazard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HazardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hazard::factory(50)->create();
    }
}
