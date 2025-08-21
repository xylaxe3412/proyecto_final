<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitSuggestion;

class HabitController extends Controller
{
    /**
     * Mostrar la vista principal de hábitos con componente interactivo
     */
    public function index()
    {
        $user = auth()->user();
        
        // Hábitos del usuario
        $userHabits = Habit::where('user_id', $user->id)
                          ->where('is_active', true)
                          ->orderBy('next_due_date')
                          ->get();
        
        // Hábitos sugeridos más populares
        $suggestedHabits = HabitSuggestion::popular(6);
        
        // Debug: verificar si hay sugerencias
        \Log::info('Sugerencias encontradas: ' . $suggestedHabits->count());
        \Log::info('Sugerencias: ' . $suggestedHabits->pluck('name')->implode(', '));
        
        // Estadísticas del usuario
        $userStats = [
            'xp' => $user->xp,
            'level' => $user->level,
            'progress' => $user->getLevelProgress(),
            'next_level_xp' => $user->getXpForNextLevel(),
            'completed_today' => Habit::completedToday($user->id)->count()
        ];
        
        return view('habits.index', compact('userHabits', 'suggestedHabits', 'userStats'));
    }

    /**
     * Obtener datos de hábitos para el componente (AJAX)
     */
    public function getData()
    {
        $user = auth()->user();
        
        $userHabits = Habit::where('user_id', $user->id)
                          ->where('is_active', true)
                          ->orderBy('next_due_date')
                          ->get()
                          ->map(function($habit) {
                              return [
                                  'id' => $habit->id,
                                  'name' => $habit->nombre ?? $habit->name,
                                  'description' => $habit->description,
                                  'categoria' => $habit->categoria,
                                  'progress_percentage' => $habit->getProgressPercentage(),
                                  'remaining_days' => $habit->remaining_days,
                                  'completed_today' => $habit->completed_today,
                                  'can_complete' => $habit->can_complete,
                                  'streak' => $habit->dias_racha,
                                  'steps' => $this->getHabitSteps($habit),
                                  'type' => 'user'
                              ];
                          });
        
        $suggestedHabits = HabitSuggestion::popular(6)->map(function($suggestion) {
            return [
                'id' => $suggestion->id,
                'name' => $suggestion->name,
                'description' => $suggestion->description,
                'categoria' => $suggestion->categoria,
                'icon' => $suggestion->icon,
                'benefits' => $suggestion->benefits,
                'steps' => $this->getSuggestionSteps($suggestion),
                'popularity' => $suggestion->popularity,
                'type' => 'suggested'
            ];
        });
        
        $userStats = [
            'xp' => $user->xp,
            'level' => $user->level,
            'progress' => $user->getLevelProgress(),
            'next_level_xp' => $user->getXpForNextLevel(),
            'completed_today' => Habit::completedToday($user->id)->count()
        ];
        
        return response()->json([
            'userHabits' => $userHabits,
            'suggestedHabits' => $suggestedHabits,
            'userStats' => $userStats
        ]);
    }

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
                'message' => 'Hábito completado (+20 XP)',
                'xp_gained' => 20,
                'habit' => [
                    'id' => $habit->id,
                    'name' => $habit->nombre ?? $habit->name,
                    'completed_today' => true,
                    'progress_percentage' => $habit->getProgressPercentage(),
                    'streak' => $habit->dias_racha
                ],
                'leveled_up' => $leveledUp,
                'new_level' => $user->level,
                'user_stats' => [
                    'xp' => $user->xp,
                    'level' => $user->level,
                    'progress' => $user->getLevelProgress(),
                    'next_level_xp' => $user->getXpForNextLevel(),
                    'completed_today' => Habit::completedToday($user->id)->count()
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
     * Deshacer la completación de un hábito
     */
    public function undo(Habit $habit)
    {
        // Verificar que el hábito pertenece al usuario autenticado
        if ($habit->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $user = auth()->user();
        $previousLevel = $user->level;
        
        $undone = $habit->undoCompletion();
        
        if ($undone) {
            // Verificar si bajó de nivel (aunque es poco probable)
            $user->refresh(); // Recargar datos del usuario
            $levelChanged = $user->level !== $previousLevel;
            
            return response()->json([
                'success' => true,
                'message' => 'Completación deshecha (-20 XP)',
                'xp_lost' => 20,
                'habit' => [
                    'id' => $habit->id,
                    'name' => $habit->nombre ?? $habit->name,
                    'completed_today' => false,
                    'progress_percentage' => $habit->getProgressPercentage(),
                    'streak' => $habit->dias_racha
                ],
                'leveled_up' => false,
                'level_changed' => $levelChanged,
                'new_level' => $user->level,
                'user_stats' => [
                    'xp' => $user->xp,
                    'level' => $user->level,
                    'progress' => $user->getLevelProgress(),
                    'next_level_xp' => $user->getXpForNextLevel(),
                    'completed_today' => Habit::completedToday($user->id)->count()
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se puede deshacer la completación de este hábito'
            ]);
        }
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

        $user = auth()->user();
        $previousLevel = $user->level;
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
        $user->addXP(5, "Crear habito personalizado: {$habit->nombre}");
        
        // Verificar si subió de nivel
        $user->refresh(); // Recargar datos del usuario
        $leveledUp = $user->level > $previousLevel;

        return response()->json([
            'success' => true,
            'message' => 'Hábito creado exitosamente (+5 XP)',
            'xp_gained' => 5,
            'habit' => [
                'id' => $habit->id,
                'name' => $habit->nombre,
                'description' => $habit->description,
                'categoria' => $habit->categoria,
                'type' => 'user'
            ],
            'leveled_up' => $leveledUp,
            'new_level' => $user->level,
            'user_stats' => [
                'xp' => $user->xp,
                'level' => $user->level,
                'progress' => $user->getLevelProgress(),
                'next_level_xp' => $user->getXpForNextLevel(),
                'completed_today' => Habit::completedToday($user->id)->count()
            ]
        ]);
    }

    /**
     * Agregar hábito sugerido a los hábitos del usuario
     */
    public function addSuggested(Request $request, HabitSuggestion $suggestion)
    {
        $request->validate([
            'duration_days' => 'nullable|integer|min:1|max:365'
        ]);

        $durationDays = $request->duration_days ?? 30;
        $startDate = now();
        
        $habit = Habit::create([
            'user_id' => auth()->id(),
            'nombre' => $suggestion->name,
            'name' => $suggestion->name,
            'description' => $suggestion->description,
            'categoria' => $suggestion->categoria,
            'frequency' => 'diario',
            'duration_days' => $durationDays,
            'current_day' => 1,
            'start_date' => $startDate->format('Y-m-d'),
            'next_due_date' => $startDate->format('Y-m-d'),
            'expected_end_date' => $startDate->addDays($durationDays)->format('Y-m-d'),
            'progreso_total' => $durationDays,
            'is_active' => true,
        ]);

        // Aumentar popularidad del hábito sugerido
        $suggestion->increasPopularity();

        // Dar XP por adoptar hábito sugerido
        auth()->user()->addXP(10, "Adoptar habito sugerido: {$suggestion->name}");

        return response()->json([
            'success' => true,
            'message' => 'Hábito agregado exitosamente (+10 XP)',
            'habit' => [
                'id' => $habit->id,
                'name' => $habit->nombre,
                'description' => $habit->description,
                'categoria' => $habit->categoria,
                'type' => 'user'
            ]
        ]);
    }

    /**
     * Crear hábito basado en una sugerencia (para el dashboard)
     */
    public function createFromSuggestion(Request $request)
    {
        $request->validate([
            'suggestion_id' => 'required|exists:habit_suggestions,id',
            'frequency' => 'nullable|in:diario,semanal',
            'duration_days' => 'nullable|integer|min:1|max:365',
            'motivation' => 'nullable|string'
        ]);

        $suggestion = HabitSuggestion::findOrFail($request->suggestion_id);
        $user = auth()->user();
        $previousLevel = $user->level;
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
            'duration_days' => $durationDays,
            'current_day' => 1,
            'start_date' => $startDate->format('Y-m-d'),
            'next_due_date' => $startDate->format('Y-m-d'),
            'expected_end_date' => $startDate->addDays($durationDays)->format('Y-m-d'),
            'progreso_total' => $durationDays,
            'is_active' => true,
        ]);

        // Aumentar popularidad del hábito sugerido
        $suggestion->increasPopularity();

        // Dar XP por adoptar hábito sugerido
        $user->addXP(10, "Adoptar habito sugerido: {$suggestion->name}");
        
        // Verificar si subió de nivel
        $user->refresh(); // Recargar datos del usuario
        $leveledUp = $user->level > $previousLevel;

        return response()->json([
            'success' => true,
            'message' => 'Hábito agregado exitosamente (+10 XP)',
            'xp_gained' => 10,
            'habit' => [
                'id' => $habit->id,
                'name' => $habit->nombre,
                'description' => $habit->description,
                'categoria' => $habit->categoria,
                'type' => 'user'
            ],
            'leveled_up' => $leveledUp,
            'new_level' => $user->level,
            'user_stats' => [
                'xp' => $user->xp,
                'level' => $user->level,
                'progress' => $user->getLevelProgress(),
                'next_level_xp' => $user->getXpForNextLevel(),
                'completed_today' => Habit::completedToday($user->id)->count()
            ]
        ]);
    }

    /**
     * Obtener pasos detallados para un hábito del usuario
     */
    private function getHabitSteps($habit)
    {
        $baseSteps = $this->getStepsByCategory($habit->categoria);
        
        // Personalizar pasos según el hábito específico
        $customSteps = [
            "Paso 1: " . ($habit->description ?? "Comenzar con {$habit->nombre}"),
        ];
        
        return array_merge($customSteps, $baseSteps);
    }

    /**
     * Obtener pasos detallados para una sugerencia de hábito
     */
    private function getSuggestionSteps($suggestion)
    {
        $baseSteps = $this->getStepsByCategory($suggestion->categoria);
        
        $customSteps = [
            "Paso 1: " . $suggestion->description,
        ];
        
        return array_merge($customSteps, $baseSteps);
    }

    /**
     * Obtener pasos base según la categoría
     */
    private function getStepsByCategory($categoria)
    {
        $steps = [
            'salud' => [
                'Paso 2: Establece un horario específico para realizar esta actividad',
                'Paso 3: Prepara todo lo necesario la noche anterior',
                'Paso 4: Comienza con 10-15 minutos y aumenta gradualmente',
                'Paso 5: Registra cómo te sientes antes y después',
                'Paso 6: Celebra cada pequeño logro para mantener la motivación'
            ],
            'productividad' => [
                'Paso 2: Define objetivos claros y medibles para cada sesión',
                'Paso 3: Elimina distracciones de tu entorno de trabajo',
                'Paso 4: Usa la técnica Pomodoro (25 min trabajo, 5 min descanso)',
                'Paso 5: Revisa tu progreso al final del día',
                'Paso 6: Ajusta tu estrategia según los resultados obtenidos'
            ],
            'bienestar' => [
                'Paso 2: Encuentra un espacio tranquilo y cómodo',
                'Paso 3: Establece una rutina previa que te ayude a entrar en estado',
                'Paso 4: Practica técnicas de respiración profunda',
                'Paso 5: Mantén una actitud de autocompasión y paciencia',
                'Paso 6: Reflexiona sobre los beneficios que estás experimentando'
            ],
            'aprendizaje' => [
                'Paso 2: Divide el contenido en pequeñas sesiones de estudio',
                'Paso 3: Toma notas activas y haz resúmenes',
                'Paso 4: Practica lo aprendido con ejercicios o ejemplos',
                'Paso 5: Enseña o explica el tema a alguien más',
                'Paso 6: Revisa y repasa regularmente para fijar el conocimiento'
            ]
        ];

        return $steps[$categoria] ?? [
            'Paso 2: Mantén consistencia en tu práctica diaria',
            'Paso 3: Ajusta la dificultad según tu progreso',
            'Paso 4: Busca apoyo en comunidades o amigos',
            'Paso 5: Documenta tu experiencia y aprendizajes',
            'Paso 6: Celebra los hitos alcanzados'
        ];
    }

    /**
     * Obtener sugerencias para API
     */
    public function getSuggestions()
    {
        $popularSuggestions = HabitSuggestion::popular(6)->map(function($suggestion) {
            return [
                'id' => $suggestion->id,
                'name' => $suggestion->name,
                'description' => $suggestion->description,
                'categoria' => $suggestion->categoria,
                'icon' => $suggestion->icon,
                'benefits' => $suggestion->benefits,
                'popularity' => $suggestion->popularity,
                'frequency_suggestions' => $suggestion->frequency_suggestions ?? ['diario'],
                'type' => 'suggested'
            ];
        });

        $categorySuggestions = [
            'salud' => HabitSuggestion::byCategory('salud', 3),
            'productividad' => HabitSuggestion::byCategory('productividad', 3),
            'bienestar' => HabitSuggestion::byCategory('bienestar', 3),
            'aprendizaje' => HabitSuggestion::byCategory('aprendizaje', 3)
        ];

        return response()->json([
            'popular' => $popularSuggestions,
            'by_category' => $categorySuggestions
        ]);
    }

    /**
     * Obtener hábitos del usuario para API
     */
    public function getUserHabits()
    {
        $user = auth()->user();
        
        $userHabits = Habit::where('user_id', $user->id)
                          ->where('is_active', true)
                          ->orderBy('next_due_date')
                          ->get()
                          ->map(function($habit) {
                              return [
                                  'id' => $habit->id,
                                  'nombre' => $habit->nombre ?? $habit->name,
                                  'name' => $habit->nombre ?? $habit->name,
                                  'description' => $habit->description,
                                  'categoria' => $habit->categoria,
                                  'progress_percentage' => $habit->getProgressPercentage(),
                                  'remaining_days' => $habit->remaining_days,
                                  'completed_today' => $habit->completed_today,
                                  'can_complete' => $habit->can_complete,
                                  'dias_racha' => $habit->dias_racha,
                                  'streak' => $habit->dias_racha,
                                  'is_completed' => $habit->completed_today,
                                  'type' => 'user'
                              ];
                          });

        $activeHabits = $userHabits->where('completed_today', false);
        $completedHabits = $userHabits->where('completed_today', true);

        return response()->json([
            'active_habits' => $activeHabits->values(),
            'completed_today' => $completedHabits->values(),
            'user_stats' => [
                'xp' => $user->xp,
                'level' => $user->level,
                'progress' => $user->getLevelProgress(),
                'next_level_xp' => $user->getXpForNextLevel(),
                'completed_today' => $completedHabits->count()
            ]
        ]);
    }
}
