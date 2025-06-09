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

        Schema::create('hazards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('name');
            $table->tinyInteger('complexity');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('rarity_id')->constrained('rarities');
            $table->string('source');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazards');
    }
};
