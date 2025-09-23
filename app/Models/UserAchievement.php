<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'unlocked_at',
        'progress'
    ];

    protected $casts = [
        'unlocked_at' => 'datetime'
    ];

    /**
     * El usuario que ha desbloqueado el logro
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El logro desbloqueado
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Verificar si el logro está completado
     */
    public function isComplete()
    {
        return $this->progress >= $this->achievement->requirement;
    }
}
