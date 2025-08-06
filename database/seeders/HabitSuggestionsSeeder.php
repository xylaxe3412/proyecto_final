<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HabitSuggestion;

class HabitSuggestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suggestions = [
            // Salud
            [
                'name' => 'Ejercicio diario',
                'description' => 'Dedica 30 minutos al día a algún tipo de ejercicio físico',
                'icon' => '🏃‍♂️',
                'categoria' => 'salud',
                'popularity' => 150,
                'frequency_suggestions' => ['diario', '5 veces por semana', '3 veces por semana'],
                'benefits' => 'Mejora tu energía, fortalece tu cuerpo y reduce el estrés'
            ],
            [
                'name' => 'Beber 8 vasos de agua',
                'description' => 'Mantente hidratado bebiendo suficiente agua durante el día',
                'icon' => '💧',
                'categoria' => 'salud',
                'popularity' => 200,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora la digestión, la piel y la energía general'
            ],
            [
                'name' => 'Dormir 8 horas',
                'description' => 'Mantén un horario de sueño regular y saludable',
                'icon' => '😴',
                'categoria' => 'salud',
                'popularity' => 180,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora la concentración, el humor y la salud general'
            ],

            // Productividad
            [
                'name' => 'Planificar el día',
                'description' => 'Dedica 10 minutos cada mañana a planificar tus tareas',
                'icon' => '📋',
                'categoria' => 'productividad',
                'popularity' => 130,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Aumenta la eficiencia y reduce el estrés'
            ],
            [
                'name' => 'Técnica Pomodoro',
                'description' => 'Trabaja en bloques de 25 minutos con descansos de 5 minutos',
                'icon' => '🍅',
                'categoria' => 'productividad',
                'popularity' => 100,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Mejora la concentración y la gestión del tiempo'
            ],
            [
                'name' => 'Revisar correos solo 3 veces',
                'description' => 'Limita la revisión de correos a momentos específicos del día',
                'icon' => '📧',
                'categoria' => 'productividad',
                'popularity' => 85,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Reduce distracciones y aumenta la productividad'
            ],

            // Bienestar
            [
                'name' => 'Meditación',
                'description' => 'Practica mindfulness o meditación durante 10-15 minutos',
                'icon' => '🧘‍♀️',
                'categoria' => 'bienestar',
                'popularity' => 140,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Reduce el estrés y mejora la claridad mental'
            ],
            [
                'name' => 'Gratitud diaria',
                'description' => 'Escribe 3 cosas por las que te sientes agradecido',
                'icon' => '🙏',
                'categoria' => 'bienestar',
                'popularity' => 120,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora el estado de ánimo y la perspectiva de vida'
            ],
            [
                'name' => 'Paseo al aire libre',
                'description' => 'Camina al menos 15 minutos al aire libre',
                'icon' => '🚶‍♂️',
                'categoria' => 'bienestar',
                'popularity' => 110,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Conecta con la naturaleza y mejora el estado de ánimo'
            ],

            // Aprendizaje
            [
                'name' => 'Leer 20 páginas',
                'description' => 'Lee al menos 20 páginas de un libro cada día',
                'icon' => '📚',
                'categoria' => 'aprendizaje',
                'popularity' => 90,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Expande conocimientos y mejora el vocabulario'
            ],
            [
                'name' => 'Aprender idiomas',
                'description' => 'Dedica 15 minutos a practicar un idioma extranjero',
                'icon' => '🌍',
                'categoria' => 'aprendizaje',
                'popularity' => 75,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Abre nuevas oportunidades y ejercita el cerebro'
            ],
            [
                'name' => 'Curso online',
                'description' => 'Avanza en un curso online o tutorial educativo',
                'icon' => '💻',
                'categoria' => 'aprendizaje',
                'popularity' => 65,
                'frequency_suggestions' => ['diario', '3 veces por semana'],
                'benefits' => 'Desarrolla nuevas habilidades profesionales'
            ],
        ];

        foreach ($suggestions as $suggestion) {
            HabitSuggestion::create($suggestion);
        }
    }
}
