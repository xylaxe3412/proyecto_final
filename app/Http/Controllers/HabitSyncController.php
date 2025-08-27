<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitSyncController extends Controller
{
    /**
     * Get available updates for user's habits
     */
    public function checkUpdates()
    {
        $user = Auth::user();
        $habits = $user->habits()
            ->where('sync_enabled', true)
            ->whereNotNull('template_id')
            ->with('template')
            ->get();

        $updates = [];

        foreach ($habits as $habit) {
            if ($habit->hasUpdateAvailable()) {
                $latestTemplate = $habit->getLatestTemplate();
                $updates[] = [
                    'habit_id' => $habit->id,
                    'habit_name' => $habit->nombre,
                    'current_version' => $habit->template_version,
                    'latest_version' => $latestTemplate->version,
                    'changelog' => $latestTemplate->changelog,
                    'template_name' => $latestTemplate->name,
                    'last_synced' => $habit->last_synced_at?->format('Y-m-d H:i:s'),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'updates_available' => count($updates),
            'updates' => $updates,
        ]);
    }

    /**
     * Sync a specific habit with its template
     */
    public function syncHabit(Request $request, $habitId)
    {
        $request->validate([
            'preserve_customizations' => 'boolean',
        ]);

        $habit = Auth::user()->habits()->findOrFail($habitId);

        if (!$habit->sync_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'La sincronización está deshabilitada para este hábito.',
            ], 400);
        }

        if (!$habit->hasUpdateAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay actualizaciones disponibles para este hábito.',
            ], 400);
        }

        $preserveCustomizations = $request->get('preserve_customizations', true);
        $synced = $habit->syncWithTemplate($preserveCustomizations);

        if ($synced) {
            // Refresh the model to get updated data
            $habit->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Hábito sincronizado exitosamente.',
                'habit' => [
                    'id' => $habit->id,
                    'nombre' => $habit->nombre,
                    'description' => $habit->description,
                    'template_version' => $habit->template_version,
                    'last_synced_at' => $habit->last_synced_at->format('Y-m-d H:i:s'),
                    'sync_notes' => $habit->sync_notes,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al sincronizar el hábito.',
        ], 500);
    }

    /**
     * Sync all eligible habits for the user
     */
    public function syncAllHabits(Request $request)
    {
        $request->validate([
            'preserve_customizations' => 'boolean',
        ]);

        $user = Auth::user();
        $habits = $user->habits()
            ->where('sync_enabled', true)
            ->whereNotNull('template_id')
            ->get();

        $preserveCustomizations = $request->get('preserve_customizations', true);
        $syncedCount = 0;
        $errors = [];

        foreach ($habits as $habit) {
            if ($habit->hasUpdateAvailable()) {
                try {
                    if ($habit->syncWithTemplate($preserveCustomizations)) {
                        $syncedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = [
                        'habit_id' => $habit->id,
                        'habit_name' => $habit->nombre,
                        'error' => $e->getMessage(),
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Se sincronizaron {$syncedCount} hábitos exitosamente.",
            'synced_count' => $syncedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Toggle sync setting for a habit
     */
    public function toggleSync(Request $request, $habitId)
    {
        $habit = Auth::user()->habits()->findOrFail($habitId);
        
        $habit->update([
            'sync_enabled' => !$habit->sync_enabled
        ]);

        return response()->json([
            'success' => true,
            'message' => $habit->sync_enabled 
                ? 'Sincronización habilitada para este hábito.' 
                : 'Sincronización deshabilitada para este hábito.',
            'sync_enabled' => $habit->sync_enabled,
        ]);
    }

    /**
     * Get sync history for a habit
     */
    public function getSyncHistory($habitId)
    {
        $habit = Auth::user()->habits()->findOrFail($habitId);
        
        return response()->json([
            'success' => true,
            'habit' => [
                'id' => $habit->id,
                'nombre' => $habit->nombre,
                'template_id' => $habit->template_id,
                'template_version' => $habit->template_version,
                'sync_enabled' => $habit->sync_enabled,
                'last_synced_at' => $habit->last_synced_at?->format('Y-m-d H:i:s'),
                'sync_notes' => $habit->sync_notes,
                'custom_settings' => $habit->custom_settings,
            ],
        ]);
    }
}
