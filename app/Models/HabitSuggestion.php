<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitSuggestion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'categoria',
        'popularity',
        'frequency_suggestions',
        'benefits'
    ];

    protected $casts = [
        'frequency_suggestions' => 'array',
    ];

    /**
     * Aumentar popularidad cuando un usuario adopta la sugerencia
     */
    public function increasPopularity()
    {
        $this->increment('popularity');
    }

    /**
     * Sugerencias más populares
     */
    public static function popular($limit = 6)
    {
        return static::orderBy('popularity', 'desc')->limit($limit)->get();
    }

    /**
     * Sugerencias por categoría
     */
    public static function byCategory($category, $limit = 3)
    {
        return static::where('categoria', $category)
                    ->orderBy('popularity', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
