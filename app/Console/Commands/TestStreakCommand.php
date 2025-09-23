<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Habit;
use Carbon\Carbon;

class TestStreakCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:streak 
                           {--user= : ID del usuario (por defecto el primer usuario)} 
                           {--days=15 : Días de racha a simular}
                           {--hours=1 : Horas restantes hasta reset}
                           {--reset : Resetear todas las rachas a 0}
                           {--first : Simular el primer hábito del día (racha = 0, ningún hábito completado hoy)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura datos de racha de prueba para testing de notificaciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user') ?? User::first()?->id;
        $days = (int) $this->option('days');
        $hours = (int) $this->option('hours');
        $reset = $this->option('reset');
        $first = $this->option('first');

        if (!$userId) {
            $this->error('No se encontró ningún usuario');
            return 1;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        // Resetear rachas si se solicita
        if ($reset) {
            $user->habits()->update([
                'dias_racha' => 0,
                'completed_today' => false,
                'last_completed_at' => null,
            ]);
            $this->info("✅ Todas las rachas han sido reseteadas a 0");
            return 0;
        }

        // Simular primer hábito del día
        if ($first) {
            $user->habits()->update([
                'dias_racha' => 0,
                'completed_today' => false,
                'last_completed_at' => now()->subDays(1), // Último completado ayer
            ]);
            $this->info("✅ Configurado para simular PRIMER hábito del día:");
            $this->line("🔥 Racha actual: 0 días");
            $this->line("📅 Hábitos completados hoy: 0");
            $this->line("🎯 Al completar un hábito, debería aparecer '¡Racha iniciada!'");
            return 0;
        }

        // Crear o actualizar hábitos del usuario con rachas
        $habits = $user->habits()->where('is_active', true)->get();
        
        if ($habits->isEmpty()) {
            // Crear un hábito de prueba si no hay ninguno
            $habit = Habit::create([
                'user_id' => $user->id,
                'nombre' => 'Hábito de Prueba - Racha',
                'descripcion' => 'Hábito creado para probar notificaciones de racha',
                'categoria' => 'bienestar',
                'frequency' => 'diario',
                'duration_days' => 30,
                'current_day' => $days,
                'dias_racha' => $days,
                'is_active' => true,
                'completed_today' => false,
                'last_completed_at' => now()->subHours(24 - $hours), // Simular último completado
                'created_at' => now()->subDays($days),
                'updated_at' => now()->subDays(1),
            ]);
            $habits = collect([$habit]);
        } else {
            // Actualizar hábitos existentes
            foreach ($habits as $habit) {
                $habit->update([
                    'dias_racha' => $days,
                    'current_day' => min($days, $habit->duration_days),
                    'completed_today' => false, // Para que aparezca la advertencia
                    'last_completed_at' => now()->subHours(24 - $hours),
                ]);
            }
        }

        $this->info("✅ Configuración de prueba aplicada:");
        $this->line("👤 Usuario: {$user->name} (ID: {$user->id})");
        $this->line("🔥 Racha simulada: {$days} días");
        $this->line("⏰ Horas hasta reset: {$hours}");
        $this->line("📊 Hábitos afectados: " . $habits->count());
        
        $this->warn("\n🧪 Para probar las notificaciones:");
        $this->line("1. Ve al dashboard (http://localhost:8000)");
        $this->line("2. Usa los botones de prueba en la esquina inferior izquierda");
        $this->line("3. O completa un hábito para ver notificaciones automáticas");
        
        if ($hours <= 2) {
            $this->error("\n⚠️  ADVERTENCIA: Con {$hours} horas restantes, deberías ver notificación de riesgo automáticamente");
        } elseif ($hours <= 6) {
            $this->warn("\n⏰ Con {$hours} horas restantes, deberías ver notificación de recordatorio automáticamente");
        }

        return 0;
    }
}
