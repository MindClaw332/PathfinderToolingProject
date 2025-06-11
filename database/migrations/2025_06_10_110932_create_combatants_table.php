<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combatants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('creature_id')->constrained();
            $table->foreignId('encounter_id')->constrained();
            $table->integer('ac');
            $table->integer('max_hp');
            $table->integer('current_hp');
            $table->integer('initiative')->default(0);
            $table->text('conditions')->nullable();
            $table->integer('initiative_bonus');
            $table->string('speed');
            $table->string('perception');
            $table->integer('fort_save');
            $table->integer('ref_save');
            $table->integer('will_save');
            $table->string('actions');
            $table->enum('type', ['player', 'monster'])->default('monster');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combatants');
    }
};