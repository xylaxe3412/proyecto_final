<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'requirement',
        'xp_reward'
    ];

    /**
     * Los usuarios que han desbloqueado este logro
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('unlocked_at', 'progress')
                    ->withTimestamps();
    }

    /**
     * Verificar si un usuario ha desbloqueado este logro
     */
    public function isUnlockedBy(User $user)
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->whereNotNull('user_achievements.unlocked_at')
            ->exists();
    }

    /**
     * Obtener el progreso de un usuario para este logro
     */
    public function getProgressFor(User $user)
    {
        $userAchievement = $this->users()->where('user_id', $user->id)->first();
        return $userAchievement ? $userAchievement->pivot->progress : 0;
    }

    /**
     * Calcular el porcentaje de progreso
     */
    public function getProgressPercentageFor(User $user)
    {
        $progress = $this->getProgressFor($user);
        return min(100, round(($progress / $this->requirement) * 100));
    }
}
