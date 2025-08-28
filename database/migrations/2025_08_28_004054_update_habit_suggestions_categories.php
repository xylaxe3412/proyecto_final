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
        Schema::table('habit_suggestions', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
        
        Schema::table('habit_suggestions', function (Blueprint $table) {
            $table->enum('categoria', ['salud', 'productividad', 'bienestar', 'aprendizaje', 'finanzas', 'relaciones'])->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habit_suggestions', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
        
        Schema::table('habit_suggestions', function (Blueprint $table) {
            $table->enum('categoria', ['salud', 'productividad', 'bienestar', 'aprendizaje'])->after('icon');
        });
    }
};
