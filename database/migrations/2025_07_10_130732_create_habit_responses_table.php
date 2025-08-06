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
        Schema::create('habit_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habit_id');
            $table->string('user_name');
            $table->integer('current_state');
            $table->json('responses'); // Almacenar todas las respuestas como JSON
            $table->timestamps();

            $table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_responses');
    }
};
