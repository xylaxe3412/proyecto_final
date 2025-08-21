<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitResponse;

class PreguntasFormController extends Controller
{
    public function show(Request $request)
    {
        // Recupera los datos del formulario anterior
        $form = $request->session()->get('habito_form', []);

        // Si no hay datos del formulario, redirige al formulario de hábitos
        if (empty($form)) {
            return redirect()->route('formulario_habito.show');
        }

        // Muestra la vista con las 10 preguntas
        return view('preguntas_form', compact('form'));
    }

    public function store(Request $request)
    {
        // Valida las respuestas del cuestionario
        $validated = $request->validate([
            'nombre' => 'required|string',
            'habito' => 'required|string', 
            'estado' => 'required|integer',
            'respuesta_1' => 'required|string',
            'respuesta_2' => 'required|string',
            'respuesta_3' => 'required|string',
            'respuesta_4' => 'required|string',
            'respuesta_5' => 'required|string',
            'respuesta_6' => 'required|string',
            'respuesta_7' => 'required|string',
            'respuesta_8' => 'required|string',
            'respuesta_9' => 'required|string',
            'respuesta_10' => 'required|string',
        ]);

        // Primero crea el hábito en la base de datos
        $habit = Habit::create([
            'user_id' => auth()->id(),
            'nombre' => $validated['habito'], // Usar el nombre del hábito
            'categoria' => 'bienestar', // Categoría por defecto, puedes hacer esto dinámico más adelante
            'dias_racha' => 0,
            'progreso_actual' => 0,
            'progreso_total' => 7,
        ]);

        // Luego crea las respuestas del cuestionario
        HabitResponse::create([
            'habit_id' => $habit->id,
            'user_name' => $validated['nombre'],
            'current_state' => $validated['estado'],
            'responses' => [
                'tiempo_objetivo' => $validated['respuesta_1'],
                'intensidad_diaria' => $validated['respuesta_2'],
                'preferencia_horaria' => $validated['respuesta_3'],
                'minutos_diarios' => $validated['respuesta_4'],
                'necesita_apoyo' => $validated['respuesta_5'],
                'recordatorios' => $validated['respuesta_6'],
                'medicion_progreso' => $validated['respuesta_7'],
                'obstaculos_previstos' => $validated['respuesta_8'],
                'celebracion_avances' => $validated['respuesta_9'],
                'motivo_principal' => $validated['respuesta_10'],
            ]
        ]);

        // Limpia la sesión
        $request->session()->forget('habito_form');

        // Redirige al dashboard con mensaje de éxito
        return redirect()->route('dashboard')
            ->with('success', '¡Hábito configurado exitosamente!');
    }
}