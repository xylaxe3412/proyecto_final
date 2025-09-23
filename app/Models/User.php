<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\AchievementUnlocked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'password',
        'xp',
        'level',
        'last_login_xp',
        'google_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_xp' => 'datetime'
    ];

    /**
     * Los logros del usuario
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at', 'progress')
                    ->withTimestamps();
    }

    /**
     * Verificar si el usuario ha desbloqueado un logro específico
     */
    public function hasAchievement(Achievement $achievement)
    {
        return $this->achievements()
            ->where('achievement_id', $achievement->id)
            ->whereNotNull('user_achievements.unlocked_at')
            ->exists();
    }

    /**
     * Obtener el progreso de un logro específico
     */
    public function getAchievementProgress(Achievement $achievement)
    {
        $userAchievement = $this->achievements()->where('achievement_id', $achievement->id)->first();
        return $userAchievement ? $userAchievement->pivot->progress : 0;
    }

    /**
     * Otorgar progreso hacia un logro
     */
    public function grantAchievementProgress(Achievement $achievement, $progress)
    {
        $userAchievement = $this->achievements()->where('achievement_id', $achievement->id)->first();
        
        if ($userAchievement) {
            // No actualizar si ya está desbloqueado
            if ($userAchievement->pivot->unlocked_at) {
                return;
            }
            // Actualizar progreso existente
            $newProgress = min($achievement->requirement, $progress);
            $this->achievements()->updateExistingPivot($achievement->id, [
                'progress' => $newProgress
            ]);

            // Si se completó el logro y no estaba desbloqueado
            if ($newProgress >= $achievement->requirement && !$userAchievement->pivot->unlocked_at) {
                $this->achievements()->updateExistingPivot($achievement->id, [
                    'unlocked_at' => now()
                ]);
                
                // Otorgar XP por desbloquear el logro
                $this->addXp($achievement->xp_reward);
                
                // Disparar evento de logro desbloqueado
                event(new AchievementUnlocked($this, $achievement));
            }
        } else {
            // Crear nuevo registro de progreso
            $this->achievements()->attach($achievement->id, [
                'progress' => min($achievement->requirement, $progress),
                'unlocked_at' => $progress >= $achievement->requirement ? now() : null
            ]);

            if ($progress >= $achievement->requirement) {
                $this->addXp($achievement->xp_reward);
                event(new AchievementUnlocked($this, $achievement));
            }
        }
    }

    /**
     * Relación con hábitos
     */
    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    /**
     * Agregar XP al usuario
     */
    public function addXP($amount, $reason = 'General')
    {
        $this->xp += $amount;
        $this->checkLevelUp();
        $this->save();
        
        // Log XP gain (opcional)
        \Log::info("Usuario {$this->id} ganó {$amount} XP por: {$reason}");
        
        return $this;
    }

    /**
     * Restar XP al usuario
     */
    public function subtractXP($amount, $reason = 'General')
    {
        $this->xp = max(0, $this->xp - $amount); // No permitir XP negativo
        $this->checkLevelDown();
        $this->save();
        
        // Log XP loss (opcional)
        \Log::info("Usuario {$this->id} perdió {$amount} XP por: {$reason}");
        
        return $this;
    }

    /**
     * Verificar si el usuario debe subir de nivel
     */
    private function checkLevelUp()
    {
        $newLevel = $this->calculateLevel($this->xp);
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
            // Aquí podrías disparar un evento de subida de nivel
        }
    }

    /**
     * Verificar si el usuario debe bajar de nivel
     */
    private function checkLevelDown()
    {
        $newLevel = $this->calculateLevel($this->xp);
        if ($newLevel < $this->level) {
            $this->level = $newLevel;
            // Aquí podrías disparar un evento de bajada de nivel si es necesario
        }
    }

    /**
     * Calcular nivel basado en XP
     */
    public function calculateLevel($xp)
    {
        // Fórmula: cada nivel requiere 100 XP adicionales
        return floor($xp / 100) + 1;
    }

    /**
     * XP necesario para el siguiente nivel
     */
    public function getXpForNextLevel()
    {
        $nextLevel = $this->level + 1;
        return ($nextLevel - 1) * 100;
    }

    /**
     * Progreso hacia el siguiente nivel (0-100%)
     */
    public function getLevelProgress()
    {
        $currentLevelXp = ($this->level - 1) * 100;
        $nextLevelXp = $this->level * 100;
        $progress = (($this->xp - $currentLevelXp) / ($nextLevelXp - $currentLevelXp)) * 100;
        return min(100, max(0, $progress));
    }

    /**
     * Obtener el total de hábitos completados (suma de progreso_actual)
     */
    public function getTotalHabitsCompleted()
    {
        return $this->habits()->sum('progreso_actual');
    }

    /**
     * Obtener hábitos completados hoy
     */
    public function getHabitsCompletedToday()
    {
        return $this->habits()->where('completed_today', true)
                             ->whereDate('last_completed_at', today())
                             ->count();
    }

    /**
     * Obtener la mejor racha de todos los hábitos
     */
    public function getBestStreak()
    {
        return $this->habits()->max('dias_racha') ?? 0;
    }

    /**
     * Obtener la racha actual más alta
     */
    public function getCurrentBestStreak()
    {
        return $this->habits()->where('is_active', true)->max('dias_racha') ?? 0;
    }

    /**
     * Obtener la fecha de desbloqueo de un logro específico
     */
    public function getAchievementUnlockDate(Achievement $achievement)
    {
        $userAchievement = $this->achievements()
            ->where('achievement_id', $achievement->id)
            ->whereNotNull('user_achievements.unlocked_at')
            ->first();
        
        if ($userAchievement && $userAchievement->pivot->unlocked_at) {
            return \Carbon\Carbon::parse($userAchievement->pivot->unlocked_at);
        }
        
        return null;
    }
}
