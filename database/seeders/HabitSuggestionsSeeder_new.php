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
        // Eliminar registros existentes
        HabitSuggestion::truncate();

        $suggestions = [
            // Salud
            [
                'name' => 'Ejercicio diario de 30 minutos',
                'description' => 'Dedica al menos 30 minutos al día a actividad física como caminar, correr o hacer ejercicios en casa',
                'icon' => '🏃‍♂️',
                'categoria' => 'salud',
                'popularity' => 150,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Mejora tu energía, fortalece tu cuerpo, reduce el estrés y mejora tu estado de ánimo'
            ],
            [
                'name' => 'Beber 2 litros de agua',
                'description' => 'Mantente hidratado bebiendo al menos 8 vasos de agua durante el día',
                'icon' => '💧',
                'categoria' => 'salud',
                'popularity' => 200,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora la digestión, la piel, la energía y la función cerebral'
            ],
            [
                'name' => 'Dormir 7-8 horas',
                'description' => 'Mantén un horario de sueño regular y saludable con 7-8 horas de descanso',
                'icon' => '😴',
                'categoria' => 'salud',
                'popularity' => 180,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora la concentración, el humor, la memoria y fortalece el sistema inmune'
            ],
            [
                'name' => 'Comer 5 porciones de frutas y verduras',
                'description' => 'Incluye al menos 5 porciones de frutas y verduras frescas en tu dieta diaria',
                'icon' => '🥗',
                'categoria' => 'salud',
                'popularity' => 120,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Aporta vitaminas, minerales y antioxidantes para una mejor salud general'
            ],

            // Productividad
            [
                'name' => 'Planificar el día cada mañana',
                'description' => 'Dedica 10-15 minutos cada mañana a planificar y priorizar tus tareas del día',
                'icon' => '📋',
                'categoria' => 'productividad',
                'popularity' => 160,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Aumenta tu enfoque, reduce el estrés y mejora la gestión del tiempo'
            ],
            [
                'name' => 'Técnica Pomodoro',
                'description' => 'Trabaja en bloques de 25 minutos con descansos de 5 minutos entre cada sesión',
                'icon' => '⏰',
                'categoria' => 'productividad',
                'popularity' => 140,
                'frequency_suggestions' => ['durante trabajo', 'días laborales'],
                'benefits' => 'Mejora la concentración, reduce la fatiga mental y aumenta la productividad'
            ],
            [
                'name' => 'Revisar emails 2 veces al día',
                'description' => 'Establece horarios específicos para revisar y responder emails en lugar de hacerlo constantemente',
                'icon' => '📧',
                'categoria' => 'productividad',
                'popularity' => 90,
                'frequency_suggestions' => ['2 veces al día', 'días laborales'],
                'benefits' => 'Reduce interrupciones, mejora el enfoque y la gestión del tiempo'
            ],

            // Bienestar
            [
                'name' => 'Meditación de 10 minutos',
                'description' => 'Practica meditación o mindfulness durante 10 minutos cada día',
                'icon' => '🧘‍♀️',
                'categoria' => 'bienestar',
                'popularity' => 130,
                'frequency_suggestions' => ['diario', 'por la mañana', 'por la noche'],
                'benefits' => 'Reduce el estrés, mejora la concentración y promueve la paz mental'
            ],
            [
                'name' => 'Escribir 3 cosas por las que estás agradecido',
                'description' => 'Anota tres cosas por las que te sientes agradecido cada día',
                'icon' => '🙏',
                'categoria' => 'bienestar',
                'popularity' => 110,
                'frequency_suggestions' => ['diario', 'por la noche'],
                'benefits' => 'Mejora el estado de ánimo, reduce la ansiedad y fomenta una perspectiva positiva'
            ],
            [
                'name' => 'Caminar 10,000 pasos',
                'description' => 'Alcanza una meta diaria de 10,000 pasos o al menos 30 minutos de caminata',
                'icon' => '🚶‍♂️',
                'categoria' => 'bienestar',
                'popularity' => 170,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora la salud cardiovascular, reduce el estrés y aumenta la energía'
            ],
            [
                'name' => 'Desconectar dispositivos antes de dormir',
                'description' => 'Evita pantallas y dispositivos electrónicos al menos 1 hora antes de ir a la cama',
                'icon' => '📱',
                'categoria' => 'bienestar',
                'popularity' => 85,
                'frequency_suggestions' => ['diario', 'por la noche'],
                'benefits' => 'Mejora la calidad del sueño, reduce la ansiedad y promueve la relajación'
            ],

            // Aprendizaje
            [
                'name' => 'Leer 20 páginas al día',
                'description' => 'Dedica tiempo a leer al menos 20 páginas de un libro cada día',
                'icon' => '📚',
                'categoria' => 'aprendizaje',
                'popularity' => 100,
                'frequency_suggestions' => ['diario', 'por la noche'],
                'benefits' => 'Expande tus conocimientos, mejora el vocabulario y estimula la creatividad'
            ],
            [
                'name' => 'Aprender algo nuevo durante 30 minutos',
                'description' => 'Dedica 30 minutos diarios a aprender algo nuevo: idioma, habilidad o curso online',
                'icon' => '🎓',
                'categoria' => 'aprendizaje',
                'popularity' => 95,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Desarrolla nuevas habilidades, mantiene la mente activa y mejora las oportunidades profesionales'
            ],
            [
                'name' => 'Escribir en un diario',
                'description' => 'Escribe tus pensamientos, experiencias y reflexiones en un diario personal',
                'icon' => '✍️',
                'categoria' => 'aprendizaje',
                'popularity' => 75,
                'frequency_suggestions' => ['diario', 'por la noche'],
                'benefits' => 'Mejora la autorreflexión, clarifica pensamientos y desarrolla habilidades de escritura'
            ],
            [
                'name' => 'Escuchar un podcast educativo',
                'description' => 'Escucha un episodio de podcast educativo mientras haces ejercicio o te desplazas',
                'icon' => '🎧',
                'categoria' => 'aprendizaje',
                'popularity' => 80,
                'frequency_suggestions' => ['diario', '3 veces por semana'],
                'benefits' => 'Aprovecha el tiempo de traslado, aprende sobre temas de interés y mantente informado'
            ]
        ];

        foreach ($suggestions as $suggestion) {
            HabitSuggestion::create($suggestion);
        }
    }
}
