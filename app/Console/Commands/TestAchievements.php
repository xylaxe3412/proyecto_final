<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Console\Command;

class TestAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:achievements {email=test@test.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar los logros de un usuario específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuario con email {$email} no encontrado");
            return 1;
        }
        
        $this->info("=== DATOS DEL USUARIO ===");
        $this->info("Nombre: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("XP: {$user->xp}");
        $this->info("Nivel: {$user->level}");
        
        $habitsCompleted = $user->getTotalHabitsCompleted();
        $this->info("Hábitos completados: {$habitsCompleted}");
        
        $bestStreak = $user->getBestStreak();
        $this->info("Mejor racha: {$bestStreak} días");
        
        $this->info("\n=== VERIFICANDO LOGROS ===");
        
        $service = app(AchievementService::class);
        $service->checkAllAchievements($user);
        
        $this->info("Logros verificados exitosamente");
        
        $this->info("\n=== LOGROS DESBLOQUEADOS ===");
        $unlockedAchievements = $user->achievements()
            ->whereNotNull('user_achievements.unlocked_at')
            ->get();
            
        if ($unlockedAchievements->count() > 0) {
            foreach ($unlockedAchievements as $achievement) {
                $this->info("✅ {$achievement->name} - {$achievement->description}");
                $progress = $achievement->pivot->progress;
                $this->info("   Progreso: {$progress}/{$achievement->requirement}");
                $this->info("   Desbloqueado: {$achievement->pivot->unlocked_at}");
            }
        } else {
            $this->warn("No hay logros desbloqueados");
        }
        
        $this->info("\n=== LOGROS EN PROGRESO ===");
        $inProgressAchievements = $user->achievements()
            ->whereNull('user_achievements.unlocked_at')
            ->get();
            
        foreach ($inProgressAchievements as $achievement) {
            $progress = $achievement->pivot->progress;
            $percentage = round(($progress / $achievement->requirement) * 100, 1);
            $this->info("🔒 {$achievement->name} - {$achievement->description}");
            $this->info("   Progreso: {$progress}/{$achievement->requirement} ({$percentage}%)");
        }
        
        return 0;
    }
}
