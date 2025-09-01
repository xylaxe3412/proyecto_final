<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_xp' => 'datetime',
        ];
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
}
