<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitSuggestion;

class HabitController extends Controller
{
    /**
     * Completar un hábito
     */
    public function complete(Habit $habit)
    {
        // Verificar que el hábito pertenece al usuario autenticado
        if ($habit->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $user = auth()->user();
        $previousLevel = $user->level;
        
        $completed = $habit->markCompleted();
        
        if ($completed) {
            // Verificar si subió de nivel
            $user->refresh(); // Recargar datos del usuario
            $leveledUp = $user->level > $previousLevel;
            
            return response()->json([
                'success' => true,
                'message' => 'Habito completado (+20 XP)',
                'xp_gained' => 20,
                'habit' => $habit->fresh(),
                'leveled_up' => $leveledUp,
                'new_level' => $user->level,
                'user_stats' => [
                    'xp' => $user->xp,
                    'level' => $user->level,
                    'progress' => $user->getLevelProgress(),
                    'next_level_xp' => $user->getXpForNextLevel()
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ya completaste este hábito hoy'
            ]);
        }
    }

    /**
     * Crear hábito desde sugerencia
     */
    public function createFromSuggestion(Request $request)
    {
        $suggestion = HabitSuggestion::findOrFail($request->suggestion_id);
        
        $durationDays = $request->duration_days ?? 30;
        $startDate = now();
        
        $habit = Habit::create([
            'user_id' => auth()->id(),
            'nombre' => $suggestion->name,
            'name' => $suggestion->name,
            'description' => $suggestion->description,
            'categoria' => $suggestion->categoria,
            'frequency' => $request->frequency ?? 'diario',
            'motivation' => $request->motivation ?? $suggestion->benefits,
            'reward' => $request->reward,
            'duration_days' => $durationDays,
            'current_day' => 1,
            'start_date' => $startDate->format('Y-m-d'),
            'next_due_date' => $startDate->format('Y-m-d'),
            'expected_end_date' => $startDate->addDays($durationDays)->format('Y-m-d'),
            'progreso_total' => $durationDays,
            'is_active' => true,
        ]);

        // Incrementar popularidad de la sugerencia
        $suggestion->increasPopularity();

        // Obtener nivel antes de dar XP
        $user = auth()->user();
        $previousLevel = $user->level;

        // Dar XP por crear hábito
        $user->addXP(5, "Crear habito: {$habit->nombre}");

        // Verificar si subió de nivel
        $user->refresh();
        $leveledUp = $user->level > $previousLevel;

        return response()->json([
            'success' => true,
            'message' => 'Habito agregado (+5 XP)',
            'xp_gained' => 5,
            'habit' => $habit,
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

    /**
     * Crear hábito personalizado desde el formulario guiado
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:diario,semanal',
            'categoria' => 'required|in:salud,productividad,bienestar,aprendizaje',
            'motivation' => 'required|string',
            'reward' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1|max:365'
        ]);

        $durationDays = $request->duration_days ?? 30;
        $startDate = now();
        
        $habit = Habit::create([
            'user_id' => auth()->id(),
            'nombre' => $request->name,
            'name' => $request->name,
            'description' => $request->description,
            'categoria' => $request->categoria,
            'frequency' => $request->frequency,
            'motivation' => $request->motivation,
            'reward' => $request->reward,
            'duration_days' => $durationDays,
            'current_day' => 1,
            'start_date' => $startDate->format('Y-m-d'),
            'next_due_date' => $startDate->format('Y-m-d'),
            'expected_end_date' => $startDate->addDays($durationDays)->format('Y-m-d'),
            'progreso_total' => $durationDays,
            'is_active' => true,
        ]);

        // Dar XP por crear hábito
        auth()->user()->addXP(5, "Crear habito personalizado: {$habit->nombre}");

        return response()->json([
            'success' => true,
            'message' => 'Habito creado exitosamente (+5 XP)',
            'xp_gained' => 5,
            'habit' => $habit
        ]);
    }

    /**
     * Obtener hábitos del usuario
     */
    public function getUserHabits()
    {
        $user = auth()->user();
        
        // Obtener hábitos activos con todos los datos necesarios
        $activeHabits = Habit::active($user->id)->map(function($habit) {
            return [
                'id' => $habit->id,
                'nombre' => $habit->nombre,
                'categoria' => $habit->categoria,
                'frequency' => $habit->frequency,
                'current_day' => $habit->current_day,
                'duration_days' => $habit->duration_days,
                'remaining_days' => $habit->remaining_days,
                'progress_percentage' => $habit->getProgressPercentage(),
                'dias_racha' => $habit->dias_racha,
                'can_complete' => $habit->can_complete,
                'completed_today' => $habit->completed_today,
                'next_due_date' => $habit->next_due_date->format('Y-m-d'),
                'motivation' => $habit->motivation,
            ];
        });

        // Obtener hábitos completados hoy
        $completedToday = Habit::completedToday($user->id)->map(function($habit) {
            return [
                'id' => $habit->id,
                'nombre' => $habit->nombre,
                'categoria' => $habit->categoria,
                'current_day' => $habit->current_day,
                'duration_days' => $habit->duration_days,
                'completed_at' => $habit->last_completed_at->format('H:i'),
            ];
        });

        return response()->json([
            'active_habits' => $activeHabits,
            'completed_today' => $completedToday,
            'user_stats' => [
                'xp' => $user->xp,
                'level' => $user->level,
                'progress' => $user->getLevelProgress(),
                'next_level_xp' => $user->getXpForNextLevel()
            ]
        ]);
    }

    /**
     * Obtener sugerencias
     */
    public function getSuggestions()
    {
        $user = auth()->user();
        
        // Obtener nombres de hábitos que el usuario ya tiene
        $userHabitNames = $user->habits()->pluck('nombre')->toArray();
        
        return response()->json([
            'popular' => HabitSuggestion::popular(8)->filter(function($suggestion) use ($userHabitNames) {
                return !in_array($suggestion->name, $userHabitNames);
            })->values(),
            'by_category' => [
                'salud' => HabitSuggestion::byCategory('salud', 4)->filter(function($suggestion) use ($userHabitNames) {
                    return !in_array($suggestion->name, $userHabitNames);
                })->values(),
                'productividad' => HabitSuggestion::byCategory('productividad', 4)->filter(function($suggestion) use ($userHabitNames) {
                    return !in_array($suggestion->name, $userHabitNames);
                })->values(),
                'bienestar' => HabitSuggestion::byCategory('bienestar', 4)->filter(function($suggestion) use ($userHabitNames) {
                    return !in_array($suggestion->name, $userHabitNames);
                })->values(),
                'aprendizaje' => HabitSuggestion::byCategory('aprendizaje', 4)->filter(function($suggestion) use ($userHabitNames) {
                    return !in_array($suggestion->name, $userHabitNames);
                })->values(),
            ]
        ]);
    }
}
