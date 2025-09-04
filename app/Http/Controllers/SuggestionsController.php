<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuggestionsController extends Controller
{
    public function getSuggestions(Request $request)
    {
        // Si se solicita refresh, devolver sugerencias aleatorias
        $refresh = $request->get('refresh', false);
        
        // Lista completa de hábitos sugeridos
        $allSuggestions = [
            // Salud
            ['id' => 1, 'name' => 'Correr 30 minutos', 'description' => 'Salir a correr por el parque durante 30 minutos', 'categoria' => 'salud', 'benefits' => 'Mejora cardiovascular y resistencia', 'popularity' => 150],
            ['id' => 2, 'name' => 'Beber 8 vasos de agua', 'description' => 'Mantenerse hidratado durante todo el día', 'categoria' => 'salud', 'benefits' => 'Mejor hidratación y salud', 'popularity' => 200],
            ['id' => 3, 'name' => 'Dormir 8 horas', 'description' => 'Mantener un horario de sueño regular', 'categoria' => 'salud', 'benefits' => 'Mejor descanso y energía', 'popularity' => 180],
            ['id' => 4, 'name' => 'Hacer yoga', 'description' => 'Practicar yoga por 20 minutos', 'categoria' => 'salud', 'benefits' => 'Flexibilidad y relajación', 'popularity' => 120],
            ['id' => 5, 'name' => 'Caminar 10,000 pasos', 'description' => 'Alcanzar la meta diaria de pasos', 'categoria' => 'salud', 'benefits' => 'Actividad física constante', 'popularity' => 170],
            ['id' => 6, 'name' => 'Hacer ejercicio en casa', 'description' => 'Rutina de ejercicios en casa por 45 minutos', 'categoria' => 'salud', 'benefits' => 'Fuerza y resistencia', 'popularity' => 140],
            ['id' => 7, 'name' => 'Estiramientos matutinos', 'description' => 'Hacer estiramientos al despertar', 'categoria' => 'salud', 'benefits' => 'Flexibilidad y energía', 'popularity' => 110],
            ['id' => 8, 'name' => 'Tomar vitaminas', 'description' => 'Tomar suplementos vitamínicos diarios', 'categoria' => 'salud', 'benefits' => 'Mejor nutrición', 'popularity' => 90],
            
            // Productividad
            ['id' => 9, 'name' => 'Escribir en un diario', 'description' => 'Dedicar 15 minutos a escribir reflexiones', 'categoria' => 'productividad', 'benefits' => 'Claridad mental y autoconocimiento', 'popularity' => 130],
            ['id' => 10, 'name' => 'Planificar el día', 'description' => 'Crear una lista de tareas para el día', 'categoria' => 'productividad', 'benefits' => 'Mejor organización', 'popularity' => 160],
            ['id' => 11, 'name' => 'Revisar emails una vez', 'description' => 'Revisar el correo solo una vez al día', 'categoria' => 'productividad', 'benefits' => 'Menos distracciones', 'popularity' => 85],
            ['id' => 12, 'name' => 'Técnica Pomodoro', 'description' => 'Trabajar en bloques de 25 minutos', 'categoria' => 'productividad', 'benefits' => 'Mayor concentración', 'popularity' => 145],
            ['id' => 13, 'name' => 'Organizar escritorio', 'description' => 'Mantener el espacio de trabajo ordenado', 'categoria' => 'productividad', 'benefits' => 'Ambiente más productivo', 'popularity' => 100],
            ['id' => 14, 'name' => 'Aprender algo nuevo', 'description' => 'Dedicar tiempo a aprender una nueva habilidad', 'categoria' => 'productividad', 'benefits' => 'Crecimiento personal', 'popularity' => 125],
            ['id' => 15, 'name' => 'Hacer networking', 'description' => 'Conectar con una persona profesional', 'categoria' => 'productividad', 'benefits' => 'Crecimiento profesional', 'popularity' => 75],
            
            // Bienestar
            ['id' => 16, 'name' => 'Meditar 10 minutos', 'description' => 'Practicar mindfulness y relajación', 'categoria' => 'bienestar', 'benefits' => 'Reducción del estrés y mayor claridad mental', 'popularity' => 190],
            ['id' => 17, 'name' => 'Practicar gratitud', 'description' => 'Escribir 3 cosas por las que estoy agradecido', 'categoria' => 'bienestar', 'benefits' => 'Mejora el estado de ánimo', 'popularity' => 165],
            ['id' => 18, 'name' => 'Escuchar música relajante', 'description' => 'Dedicar tiempo a la música que me relaja', 'categoria' => 'bienestar', 'benefits' => 'Relajación y bienestar', 'popularity' => 135],
            ['id' => 19, 'name' => 'Respiración profunda', 'description' => 'Ejercicios de respiración consciente', 'categoria' => 'bienestar', 'benefits' => 'Reducción de la ansiedad', 'popularity' => 115],
            ['id' => 20, 'name' => 'Tiempo en la naturaleza', 'description' => 'Pasar tiempo al aire libre', 'categoria' => 'bienestar', 'benefits' => 'Conexión con la naturaleza', 'popularity' => 155],
            ['id' => 21, 'name' => 'Desconectar dispositivos', 'description' => 'Una hora sin pantallas', 'categoria' => 'bienestar', 'benefits' => 'Descanso mental', 'popularity' => 105],
            ['id' => 22, 'name' => 'Baño relajante', 'description' => 'Tomar un baño con sales relajantes', 'categoria' => 'bienestar', 'benefits' => 'Relajación física', 'popularity' => 95],
            
            // Aprendizaje
            ['id' => 23, 'name' => 'Leer 30 páginas', 'description' => 'Dedicar tiempo a la lectura diaria', 'categoria' => 'aprendizaje', 'benefits' => 'Expansión del conocimiento y vocabulario', 'popularity' => 175],
            ['id' => 24, 'name' => 'Ver un documental', 'description' => 'Aprender algo nuevo viendo documentales', 'categoria' => 'aprendizaje', 'benefits' => 'Conocimiento visual', 'popularity' => 110],
            ['id' => 25, 'name' => 'Hacer un curso online', 'description' => 'Avanzar en un curso de mi interés', 'categoria' => 'aprendizaje', 'benefits' => 'Desarrollo de habilidades', 'popularity' => 140],
            ['id' => 26, 'name' => 'Practicar idiomas', 'description' => 'Dedicar tiempo a aprender un nuevo idioma', 'categoria' => 'aprendizaje', 'benefits' => 'Habilidades lingüísticas', 'popularity' => 130],
            ['id' => 27, 'name' => 'Escribir código', 'description' => 'Practicar programación diariamente', 'categoria' => 'aprendizaje', 'benefits' => 'Habilidades técnicas', 'popularity' => 120],
            ['id' => 28, 'name' => 'Resolver puzzles', 'description' => 'Ejercitar la mente con juegos mentales', 'categoria' => 'aprendizaje', 'benefits' => 'Agilidad mental', 'popularity' => 85],
            
            // Finanzas
            ['id' => 29, 'name' => 'Revisar gastos diarios', 'description' => 'Anotar todos los gastos del día', 'categoria' => 'finanzas', 'benefits' => 'Control financiero', 'popularity' => 95],
            ['id' => 30, 'name' => 'Ahorrar dinero', 'description' => 'Apartar una cantidad fija para ahorro', 'categoria' => 'finanzas', 'benefits' => 'Seguridad financiera', 'popularity' => 150],
            ['id' => 31, 'name' => 'Leer sobre inversiones', 'description' => 'Estudiar opciones de inversión', 'categoria' => 'finanzas', 'benefits' => 'Conocimiento financiero', 'popularity' => 80],
            ['id' => 32, 'name' => 'Revisar presupuesto', 'description' => 'Evaluar y ajustar el presupuesto mensual', 'categoria' => 'finanzas', 'benefits' => 'Mejor planificación', 'popularity' => 100],
            
            // Relaciones
            ['id' => 33, 'name' => 'Llamar a la familia', 'description' => 'Mantener contacto con familiares', 'categoria' => 'relaciones', 'benefits' => 'Vínculos familiares fuertes', 'popularity' => 145],
            ['id' => 34, 'name' => 'Tiempo con amigos', 'description' => 'Dedicar tiempo de calidad a las amistades', 'categoria' => 'relaciones', 'benefits' => 'Relaciones sociales', 'popularity' => 125],
            ['id' => 35, 'name' => 'Escribir mensajes positivos', 'description' => 'Enviar mensajes de apoyo a seres queridos', 'categoria' => 'relaciones', 'benefits' => 'Fortalece vínculos', 'popularity' => 90],
            ['id' => 36, 'name' => 'Escuchar activamente', 'description' => 'Practicar la escucha activa en conversaciones', 'categoria' => 'relaciones', 'benefits' => 'Mejores relaciones', 'popularity' => 110],
        ];

        if ($refresh) {
            // Mezclar y tomar elementos aleatorios
            $shuffled = collect($allSuggestions)->shuffle();
            $popular = $shuffled->take(8)->values(); // Tomar 8 sugerencias aleatorias
        } else {
            // Comportamiento normal - ordenar por popularidad
            $popular = collect($allSuggestions)->sortByDesc('popularity')->take(8)->values();
        }

        // Agrupar por categoría
        $byCategory = collect($allSuggestions)->groupBy('categoria')->map(function ($items) {
            return $items->shuffle()->take(4)->values(); // 4 aleatorios por categoría
        });

        return response()->json([
            'popular' => $popular,
            'by_category' => $byCategory
        ]);
    }
}
