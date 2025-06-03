<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('hazard_pathfinder_trait', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hazard_id')->constrained('hazards');
            $table->foreignId('pathfinder_trait_id')->constrained('pathfinder_traits');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_trait');
    }
};
