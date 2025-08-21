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
        Schema::table('habits', function (Blueprint $table) {
            $table->integer('duration_days')->default(30); // Duración total estimada (ej: 30 días)
            $table->integer('current_day')->default(1); // Día actual del progreso (ej: día 5 de 30)
            $table->date('next_due_date')->default(now()->format('Y-m-d')); // Próxima fecha de seguimiento
            $table->boolean('is_active')->default(true); // Si el hábito está activo
            $table->date('start_date')->default(now()->format('Y-m-d')); // Fecha de inicio
            $table->date('expected_end_date')->nullable(); // Fecha estimada de finalización
            $table->boolean('completed_today')->default(false); // Si fue completado hoy
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn([
                'duration_days', 
                'current_day', 
                'next_due_date', 
                'is_active', 
                'start_date', 
                'expected_end_date',
                'completed_today'
            ]);
        });
    }
};
