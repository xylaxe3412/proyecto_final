<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitResponse extends Model
{
    protected $fillable = [
        'habit_id',
        'user_name',
        'current_state',
        'responses'
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
