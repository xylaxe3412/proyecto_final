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
        // Tabla de logros disponibles
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon')->nullable(); // Nombre del archivo de icono o clase CSS
            $table->string('type'); // Tipo de logro (nivel, racha, habitos_completados, etc.)
            $table->integer('requirement'); // Valor requerido para desbloquear el logro
            $table->integer('xp_reward'); // XP que se otorga al desbloquear
            $table->timestamps();
        });

        // Tabla pivote para logros desbloqueados por usuario
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->timestamp('unlocked_at')->nullable();
            $table->integer('progress')->default(0); // Progreso actual hacia el logro
            $table->timestamps();
            
            // Un usuario solo puede tener una vez cada logro
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
    }
};
