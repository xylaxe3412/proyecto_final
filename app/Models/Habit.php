<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'nombre', 
        'name',
        'description',
        'categoria', 
        'dias_racha', 
        'progreso_actual', 
        'progreso_total',
        'frequency',
        'motivation',
        'reward',
        'is_completed',
        'last_completed_at',
        'current_state',
        'answers',
        // Nuevos campos para seguimiento diario
        'duration_days',
        'current_day',
        'next_due_date',
        'is_active',
        'start_date',
        'expected_end_date',
        'completed_today',
    ];

    protected $casts = [
        'answers' => 'array',
        'next_due_date' => 'date',
        'start_date' => 'date',
        'expected_end_date' => 'date',
        'last_completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_active' => 'boolean',
        'completed_today' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(HabitResponse::class);
    }

    /**
     * Marcar hábito como completado hoy
     */
    public function markCompleted()
    {
        // Verificar que no haya sido completado hoy y que sea el día correcto
        if ($this->completed_today || !$this->next_due_date->isToday()) {
            return false;
        }

        // Marcar como completado
        $this->update([
            'completed_today' => true,
            'last_completed_at' => now(),
            'current_day' => $this->current_day + 1,
            'dias_racha' => $this->dias_racha + 1,
            'progreso_actual' => $this->progreso_actual + 1,
            'next_due_date' => now()->addDay()->format('Y-m-d'),
        ]);

        // Verificar si se completó el período total
        if ($this->current_day >= $this->duration_days) {
            $this->update(['is_active' => false]);
        }

        // Dar XP al usuario
        $this->user->addXP(20, "Completar habito: {$this->nombre}");

        return true;
    }

    /**
     * Resetear estado diario (ejecutar automáticamente cada día)
     */
    public function resetDailyStatus()
    {
        if ($this->next_due_date->isToday() && $this->completed_today) {
            $this->update(['completed_today' => false]);
        }
        
        // Si se saltó un día, reiniciar racha
        if ($this->next_due_date->isPast() && !$this->completed_today) {
            $this->update(['dias_racha' => 0]);
        }
    }

    /**
     * Verificar si está completado hoy
     */
    public function isCompletedToday()
    {
        return $this->completed_today && $this->next_due_date->isToday();
    }

    /**
     * Obtener progreso en porcentaje
     */
    public function getProgressPercentage()
    {
        return $this->duration_days > 0 ? round(($this->current_day / $this->duration_days) * 100, 1) : 0;
    }

    /**
     * Obtener días restantes
     */
    public function getRemainingDaysAttribute()
    {
        return max(0, $this->duration_days - $this->current_day);
    }

    /**
     * Verificar si está disponible para completar hoy
     */
    public function getCanCompleteAttribute()
    {
        return $this->is_active && 
               !$this->completed_today && 
               $this->next_due_date->isToday();
    }

    /**
     * Hábitos activos (disponibles para completar)
     */
    public static function active($userId)
    {
        return static::where('user_id', $userId)
                    ->where('is_active', true)
                    ->where('next_due_date', '<=', now()->format('Y-m-d'))
                    ->orderBy('next_due_date')
                    ->get();
    }

    /**
     * Hábitos completados hoy
     */
    public static function completedToday($userId)
    {
        return static::where('user_id', $userId)
                    ->where('completed_today', true)
                    ->whereDate('last_completed_at', today())
                    ->get();
    }
}
