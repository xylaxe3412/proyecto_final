<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Mostrar la página de logros del usuario
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener todos los logros y organizarlos por tipo
        $achievements = Achievement::all()->groupBy('type');
        
        // Organizar los logros por categoría con su progreso
        $achievementsByCategory = [
            'level' => [
                'title' => 'Logros de Nivel',
                'description' => 'Logros desbloqueados al alcanzar ciertos niveles',
                'achievements' => $achievements->get('level', collect())
            ],
            'habits_completed' => [
                'title' => 'Logros de Hábitos',
                'description' => 'Logros desbloqueados al completar hábitos',
                'achievements' => $achievements->get('habits_completed', collect())
            ],
            'streak' => [
                'title' => 'Logros de Racha',
                'description' => 'Logros desbloqueados al mantener rachas consecutivas',
                'achievements' => $achievements->get('streak', collect())
            ],
            'total_xp' => [
                'title' => 'Logros de XP',
                'description' => 'Logros desbloqueados al acumular XP',
                'achievements' => $achievements->get('total_xp', collect())
            ]
        ];

        return view('achievements.index', [
            'achievementsByCategory' => $achievementsByCategory,
            'user' => $user
        ]);
    }

    /**
     * Comprobar el progreso de logros después de una acción
     */
    public function checkAchievements()
    {
        $user = Auth::user();
        
        // Verificar todos los tipos de logros
        $this->achievementService->checkAllAchievements($user);
        
        return response()->json([
            'message' => 'Logros verificados correctamente'
        ]);
    }

    /**
     * Obtener el progreso de un logro específico
     */
    public function getProgress(Achievement $achievement)
    {
        $user = Auth::user();
        $progress = $user->getAchievementProgress($achievement);
        $percentage = $achievement->getProgressPercentageFor($user);
        $isUnlocked = $user->hasAchievement($achievement);
        
        return response()->json([
            'achievement' => $achievement->name,
            'progress' => $progress,
            'requirement' => $achievement->requirement,
            'percentage' => $percentage,
            'is_unlocked' => $isUnlocked,
            'unlocked_at' => $isUnlocked ? $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first()->pivot->unlocked_at : null
        ]);
    }
}
