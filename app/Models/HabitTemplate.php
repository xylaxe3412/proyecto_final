<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'name',
        'description',
        'categoria',
        'version',
        'content',
        'quiz_questions',
        'steps',
        'tips',
        'motivational_quotes',
        'is_active',
        'duration_days',
        'difficulty_level',
        'changelog',
    ];

    protected $casts = [
        'content' => 'array',
        'quiz_questions' => 'array',
        'steps' => 'array',
        'tips' => 'array',
        'motivational_quotes' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get habits using this template
     */
    public function habits()
    {
        return $this->hasMany(Habit::class, 'template_id', 'template_id');
    }

    /**
     * Get the latest version of a template
     */
    public static function getLatestVersion($templateId)
    {
        return static::where('template_id', $templateId)
            ->where('is_active', true)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Check if there's a newer version available
     */
    public function hasNewerVersion()
    {
        $latest = static::getLatestVersion($this->template_id);
        return $latest && version_compare($latest->version, $this->version, '>');
    }

    /**
     * Get all templates with newer versions available
     */
    public static function getTemplatesWithUpdates()
    {
        return static::selectRaw('template_id, MAX(version) as latest_version')
            ->where('is_active', true)
            ->groupBy('template_id')
            ->get();
    }
}
