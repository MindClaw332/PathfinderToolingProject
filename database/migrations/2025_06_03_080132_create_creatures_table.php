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

        Schema::create('creatures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('size_id')->constrained('sizes');
            $table->bigInteger('level');
            $table->bigInteger('hp');
            $table->bigInteger('ac');
            $table->bigInteger('fortitude');
            $table->bigInteger('reflex');
            $table->bigInteger('will');
            $table->bigInteger('perception');
            $table->text('senses');
            $table->string('speed');
            $table->foreignId('rarity_id')->constrained('rarities');
            $table->boolean('custom');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creatures');
    }
};
