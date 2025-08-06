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
        Schema::create('habit_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('🎯');
            $table->enum('categoria', ['salud', 'productividad', 'bienestar', 'aprendizaje']);
            $table->integer('popularity')->default(0);
            $table->json('frequency_suggestions')->nullable(); // ["diario", "3 veces por semana", etc.]
            $table->text('benefits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_suggestions');
    }
};
