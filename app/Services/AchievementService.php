<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Events\AchievementUnlocked;

class AchievementService
{
    /**
     * Verificar y actualizar logros relacionados con el nivel
     */
    public function checkLevelAchievements(User $user)
    {
        $levelAchievements = Achievement::where('type', 'level')->get();
        
        foreach ($levelAchievements as $achievement) {
            $this->updateAchievementProgress($user, $achievement, $user->level);
        }
    }

    /**
     * Verificar y actualizar logros relacionados con hábitos completados
     */
    public function checkHabitsCompletedAchievements(User $user, $habitsCompleted)
    {
        $habitsAchievements = Achievement::where('type', 'habits_completed')->get();
        
        foreach ($habitsAchievements as $achievement) {
            $this->updateAchievementProgress($user, $achievement, $habitsCompleted);
        }
    }

    /**
     * Verificar y actualizar logros relacionados con XP acumulada
     */
    public function checkXpAchievements(User $user)
    {
        $xpAchievements = Achievement::where('type', 'total_xp')->get();
        
        foreach ($xpAchievements as $achievement) {
            $this->updateAchievementProgress($user, $achievement, $user->xp);
        }
    }

    /**
     * Verificar y actualizar logros relacionados con rachas
     */
    public function checkStreakAchievements(User $user)
    {
        $streakAchievements = Achievement::where('type', 'streak')->get();
        
        // Obtener la racha más alta de todos los hábitos del usuario
        $bestStreak = $user->habits()->max('dias_racha') ?? 0;
        
        foreach ($streakAchievements as $achievement) {
            $this->updateAchievementProgress($user, $achievement, $bestStreak);
        }
    }

    /**
     * Actualizar el progreso de un logro específico
     */
    private function updateAchievementProgress(User $user, Achievement $achievement, $progress)
    {
        $existingAchievement = $user->achievements()
            ->where('achievement_id', $achievement->id)
            ->first();
            
        if ($existingAchievement) {
            // Si ya está desbloqueado, no hacer nada
            if ($existingAchievement->pivot->unlocked_at) {
                return;
            }
            
            // Actualizar progreso
            $user->achievements()->updateExistingPivot($achievement->id, [
                'progress' => $progress,
                'unlocked_at' => $progress >= $achievement->requirement ? now() : null
            ]);
            
            // Si se acaba de desbloquear, dar recompensa
            if ($progress >= $achievement->requirement) {
                $user->addXp($achievement->xp_reward, "Logro desbloqueado: {$achievement->name}");
                event(new AchievementUnlocked($user, $achievement));
            }
        } else {
            // Crear nuevo registro
            $user->achievements()->attach($achievement->id, [
                'progress' => $progress,
                'unlocked_at' => $progress >= $achievement->requirement ? now() : null
            ]);
            
            // Si se desbloquea inmediatamente, dar recompensa
            if ($progress >= $achievement->requirement) {
                $user->addXp($achievement->xp_reward, "Logro desbloqueado: {$achievement->name}");
                event(new AchievementUnlocked($user, $achievement));
            }
        }
    }

    /**
     * Verificar todos los logros de un usuario
     */
    public function checkAllAchievements(User $user)
    {
        // Verificar logros de nivel
        $this->checkLevelAchievements($user);
        
        // Verificar logros de XP
        $this->checkXpAchievements($user);
        
        // Verificar logros de hábitos completados
        $habitsCompleted = $user->getTotalHabitsCompleted();
        $this->checkHabitsCompletedAchievements($user, $habitsCompleted);
        
        // Verificar logros de rachas
        $this->checkStreakAchievements($user);
    }

    /**
     * Crear logros predeterminados del sistema
     */
    public function createDefaultAchievements()
    {
        $achievements = [
            // Logros de nivel
            [
                'name' => 'Principiante',
                'description' => 'Alcanza el nivel 5',
                'type' => 'level',
                'requirement' => 5,
                'xp_reward' => 100,
                'icon' => 'user-graduate'
            ],
            [
                'name' => 'Aprendiz',
                'description' => 'Alcanza el nivel 10',
                'type' => 'level',
                'requirement' => 10,
                'xp_reward' => 200,
                'icon' => 'certificate'
            ],
            [
                'name' => 'Maestro',
                'description' => 'Alcanza el nivel 20',
                'type' => 'level',
                'requirement' => 20,
                'xp_reward' => 500,
                'icon' => 'crown'
            ],
            
            // Logros de hábitos completados
            [
                'name' => 'Primeros Pasos',
                'description' => 'Completa 10 hábitos',
                'type' => 'habits_completed',
                'requirement' => 10,
                'xp_reward' => 50,
                'icon' => 'check-circle'
            ],
            [
                'name' => 'Constancia',
                'description' => 'Completa 50 hábitos',
                'type' => 'habits_completed',
                'requirement' => 50,
                'xp_reward' => 150,
                'icon' => 'clipboard-check'
            ],
            [
                'name' => 'Disciplina Total',
                'description' => 'Completa 100 hábitos',
                'type' => 'habits_completed',
                'requirement' => 100,
                'xp_reward' => 300,
                'icon' => 'tasks'
            ],
            
            // Logros de rachas
            [
                'name' => 'Consistente',
                'description' => 'Mantén una racha de 7 días',
                'type' => 'streak',
                'requirement' => 7,
                'xp_reward' => 75,
                'icon' => 'fire'
            ],
            [
                'name' => 'Determinado',
                'description' => 'Mantén una racha de 14 días',
                'type' => 'streak',
                'requirement' => 14,
                'xp_reward' => 150,
                'icon' => 'fire-alt'
            ],
            [
                'name' => 'Imparable',
                'description' => 'Mantén una racha de 30 días',
                'type' => 'streak',
                'requirement' => 30,
                'xp_reward' => 300,
                'icon' => 'bolt'
            ],
            [
                'name' => 'Leyenda de Rachas',
                'description' => 'Mantén una racha de 60 días',
                'type' => 'streak',
                'requirement' => 60,
                'xp_reward' => 500,
                'icon' => 'meteor'
            ],
            [
                'name' => 'Maestro de la Disciplina',
                'description' => 'Mantén una racha de 100 días',
                'type' => 'streak',
                'requirement' => 100,
                'xp_reward' => 1000,
                'icon' => 'dragon'
            ],
            
            // Logros de XP total
            [
                'name' => 'Iniciado',
                'description' => 'Acumula 1000 XP',
                'type' => 'total_xp',
                'requirement' => 1000,
                'xp_reward' => 100,
                'icon' => 'star'
            ],
            [
                'name' => 'Veterano',
                'description' => 'Acumula 5000 XP',
                'type' => 'total_xp',
                'requirement' => 5000,
                'xp_reward' => 300,
                'icon' => 'medal'
            ],
            [
                'name' => 'Leyenda',
                'description' => 'Acumula 10000 XP',
                'type' => 'total_xp',
                'requirement' => 10000,
                'xp_reward' => 500,
                'icon' => 'award'
            ],
        ];

        foreach ($achievements as $achievementData) {
            Achievement::updateOrCreate(
                ['name' => $achievementData['name']],
                $achievementData
            );
        }
    }
}