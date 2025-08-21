<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;

class QuizController extends Controller
{
    /**
     * Mostrar la página del quiz
     */
    public function show()
    {
        return view('habit-quiz');
    }

    /**
     * Completar el quiz y otorgar XP
     */
    public function complete(Request $request)
    {
        $request->validate([
            'habit_id' => 'nullable|exists:habits,id',
            'score' => 'required|integer|min:0',
            'total' => 'required|integer|min:1'
        ]);

        $user = auth()->user();
        $previousLevel = $user->level;

        // Otorgar +5 XP por completar el quiz
        $user->addXP(5, "Completar quiz de hábitos");

        // Verificar si subió de nivel
        $user->refresh();
        $leveledUp = $user->level > $previousLevel;

        return response()->json([
            'success' => true,
            'message' => 'Quiz completado (+5 XP)',
            'xp_gained' => 5,
            'score' => $request->score,
            'total' => $request->total,
            'leveled_up' => $leveledUp,
            'new_level' => $user->level,
            'user_stats' => [
                'xp' => $user->xp,
                'level' => $user->level,
                'progress' => $user->getLevelProgress(),
                'next_level_xp' => $user->getXpForNextLevel()
            ]
        ]);
    }
}
