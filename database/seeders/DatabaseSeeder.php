<?php

namespace Database\Seeders;

use App\Models\PathfinderTrait;
use App\Models\User;
use Illuminate\Support\Facades\Process;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([
            RaritySeeder::class,
            SizeSeeder::class,
            TypeSeeder::class,
            // PathfinderTraitSeeder::class,,
            // CreatureSeeder::class,
        ]);
        Process::timeout(800)->run("cd ./scraper && go run .");
        $this->call(HazardSeeder::class);
    }
}
