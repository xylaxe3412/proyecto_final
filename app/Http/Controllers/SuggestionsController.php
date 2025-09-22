<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabitSuggestion;

class SuggestionsController extends Controller
{
    public function getSuggestions(Request $request)
    {
        // Si se solicita refresh, devolver sugerencias aleatorias
        $refresh = $request->get('refresh', false);
        
        // Obtener sugerencias de la base de datos
        if ($refresh) {
            // Sugerencias aleatorias
            $popularSuggestions = HabitSuggestion::inRandomOrder()->take(8)->get();
        } else {
            // Sugerencias más populares
            $popularSuggestions = HabitSuggestion::popular(8);
        }
        
        // Mapear las sugerencias al formato esperado
        $popular = $popularSuggestions->map(function($suggestion) {
            return [
                'id' => $suggestion->id,
                'name' => $suggestion->name,
                'description' => $suggestion->description,
                'categoria' => $suggestion->categoria,
                'benefits' => $suggestion->benefits,
                'popularity' => $suggestion->popularity,
                'icon' => $suggestion->icon ?? '',
                'frequency_suggestions' => $suggestion->frequency_suggestions ?? ['diario'],
                'type' => 'suggested'
            ];
        })->values();
        
        // Agrupar por categorías
        $byCategory = $popular->groupBy('categoria')->map(function ($items) {
            return $items->values(); // Reset keys
        });

        return response()->json([
            'popular' => $popular,
            'by_category' => $byCategory
        ]);
    }
}