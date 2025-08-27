<?php

namespace Database\Seeders;

use App\Models\HabitTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'template_id' => 'ejercicio_diario',
                'name' => 'Ejercicio Diario',
                'description' => 'Incorpora actividad física regular en tu rutina diaria para mejorar tu salud y bienestar.',
                'categoria' => 'salud',
                'version' => '1.0',
                'duration_days' => 30,
                'difficulty_level' => 'beginner',
                'content' => [
                    'goals' => ['Aumentar la energía', 'Mejorar la condición física', 'Reducir el estrés'],
                    'benefits' => ['Mayor resistencia', 'Mejor estado de ánimo', 'Salud cardiovascular'],
                    'tips' => [
                        'Comienza con 15-20 minutos diarios',
                        'Encuentra una actividad que disfrutes',
                        'Establece un horario fijo',
                        'Escucha a tu cuerpo y descansa cuando sea necesario'
                    ]
                ],
                'steps' => [
                    'Elige tu tipo de ejercicio favorito',
                    'Prepara tu ropa y equipo la noche anterior',
                    'Comienza con un calentamiento ligero',
                    'Realiza tu rutina de ejercicio',
                    'Finaliza con estiramientos'
                ],
                'tips' => [
                    'La consistencia es más importante que la intensidad',
                    'Celebra cada pequeño logro',
                    'Encuentra un compañero de ejercicio para motivación'
                ],
                'motivational_quotes' => [
                    'Tu cuerpo puede hacerlo. Es tu mente a la que tienes que convencer.',
                    'El ejercicio es una celebración de lo que tu cuerpo puede hacer.',
                    'No se trata de ser perfecto, se trata de ser mejor que ayer.'
                ],
                'changelog' => 'Versión inicial del template de ejercicio diario.',
            ],
            [
                'template_id' => 'meditacion_mindfulness',
                'name' => 'Meditación y Mindfulness',
                'description' => 'Desarrolla una práctica de meditación para reducir el estrés y mejorar tu bienestar mental.',
                'categoria' => 'bienestar',
                'version' => '1.0',
                'duration_days' => 21,
                'difficulty_level' => 'beginner',
                'content' => [
                    'goals' => ['Reducir el estrés', 'Mejorar la concentración', 'Aumentar la autoconciencia'],
                    'benefits' => ['Mayor calma mental', 'Mejor manejo emocional', 'Claridad mental'],
                    'techniques' => ['Respiración consciente', 'Escaneo corporal', 'Meditación caminando']
                ],
                'steps' => [
                    'Encuentra un lugar tranquilo',
                    'Adopta una postura cómoda',
                    'Enfócate en tu respiración',
                    'Observa tus pensamientos sin juzgar',
                    'Termina gradualmente'
                ],
                'tips' => [
                    'Empieza con 5-10 minutos diarios',
                    'No busques "vaciar" la mente',
                    'La práctica regular es clave'
                ],
                'motivational_quotes' => [
                    'La paz viene de dentro. No la busques fuera.',
                    'Meditar no es huir de la realidad, es encontrar la paz en ella.',
                    'Un momento de tranquilidad puede cambiar todo tu día.'
                ],
                'changelog' => 'Versión inicial del template de meditación.',
            ],
            [
                'template_id' => 'lectura_diaria',
                'name' => 'Lectura Diaria',
                'description' => 'Cultiva el hábito de la lectura para expandir tu conocimiento y desarrollar tu mente.',
                'categoria' => 'aprendizaje',
                'version' => '1.0',
                'duration_days' => 30,
                'difficulty_level' => 'beginner',
                'content' => [
                    'goals' => ['Ampliar conocimientos', 'Mejorar vocabulario', 'Desarrollar pensamiento crítico'],
                    'benefits' => ['Mayor cultura general', 'Mejor capacidad de análisis', 'Relajación mental'],
                    'genres' => ['Ficción', 'No ficción', 'Desarrollo personal', 'Historia', 'Ciencia']
                ],
                'steps' => [
                    'Elige un libro que te interese',
                    'Establece un tiempo fijo para leer',
                    'Encuentra un lugar cómodo y sin distracciones',
                    'Lee al menos 15-20 páginas',
                    'Reflexiona sobre lo leído'
                ],
                'tips' => [
                    'Lleva siempre un libro contigo',
                    'Toma notas de ideas importantes',
                    'Únete a un club de lectura'
                ],
                'motivational_quotes' => [
                    'Los libros son una ventana a mundos infinitos.',
                    'Leer es viajar sin moverse del lugar.',
                    'Un libro abierto es un cerebro que habla.'
                ],
                'changelog' => 'Versión inicial del template de lectura diaria.',
            ],
            [
                'template_id' => 'organizacion_productividad',
                'name' => 'Organización y Productividad',
                'description' => 'Mejora tu organización personal y aumenta tu productividad diaria.',
                'categoria' => 'productividad',
                'version' => '1.0',
                'duration_days' => 30,
                'difficulty_level' => 'intermediate',
                'content' => [
                    'goals' => ['Ser más organizado', 'Aumentar la productividad', 'Reducir el estrés'],
                    'benefits' => ['Mayor eficiencia', 'Menos estrés', 'Más tiempo libre'],
                    'methods' => ['GTD', 'Pomodoro', 'Time blocking', 'Matriz de Eisenhower']
                ],
                'steps' => [
                    'Planifica tu día la noche anterior',
                    'Prioriza tus tareas más importantes',
                    'Elimina distracciones',
                    'Trabaja en bloques de tiempo',
                    'Revisa tu progreso al final del día'
                ],
                'tips' => [
                    'Una tarea a la vez',
                    'Usa listas de tareas',
                    'Delega cuando sea posible'
                ],
                'motivational_quotes' => [
                    'La organización no es perfección, es eficiencia.',
                    'Un lugar para cada cosa y cada cosa en su lugar.',
                    'La productividad no es hacer más cosas, es hacer las cosas correctas.'
                ],
                'changelog' => 'Versión inicial del template de organización.',
            ]
        ];

        foreach ($templates as $template) {
            HabitTemplate::create($template);
        }

        // Crear versiones actualizadas para demostrar la funcionalidad
        $updatedTemplates = [
            [
                'template_id' => 'ejercicio_diario',
                'name' => 'Ejercicio Diario',
                'description' => 'Incorpora actividad física regular en tu rutina diaria para mejorar tu salud y bienestar. Ahora con seguimiento de progreso mejorado.',
                'categoria' => 'salud',
                'version' => '1.1',
                'duration_days' => 30,
                'difficulty_level' => 'beginner',
                'content' => [
                    'goals' => ['Aumentar la energía', 'Mejorar la condición física', 'Reducir el estrés', 'Construir rutinas saludables'],
                    'benefits' => ['Mayor resistencia', 'Mejor estado de ánimo', 'Salud cardiovascular', 'Mejor calidad del sueño'],
                    'tips' => [
                        'Comienza con 15-20 minutos diarios',
                        'Encuentra una actividad que disfrutes',
                        'Establece un horario fijo',
                        'Escucha a tu cuerpo y descansa cuando sea necesario',
                        'Registra tu progreso diariamente'
                    ],
                    'new_features' => ['Seguimiento de progreso', 'Rutinas personalizables', 'Recordatorios inteligentes']
                ],
                'steps' => [
                    'Elige tu tipo de ejercicio favorito',
                    'Prepara tu ropa y equipo la noche anterior',
                    'Comienza con un calentamiento ligero',
                    'Realiza tu rutina de ejercicio',
                    'Registra tu sesión en la app',
                    'Finaliza con estiramientos'
                ],
                'tips' => [
                    'La consistencia es más importante que la intensidad',
                    'Celebra cada pequeño logro',
                    'Encuentra un compañero de ejercicio para motivación',
                    'Usa la función de seguimiento para ver tu progreso'
                ],
                'motivational_quotes' => [
                    'Tu cuerpo puede hacerlo. Es tu mente a la que tienes que convencer.',
                    'El ejercicio es una celebración de lo que tu cuerpo puede hacer.',
                    'No se trata de ser perfecto, se trata de ser mejor que ayer.',
                    'Cada día que te ejercitas es un día que inviertes en tu futuro.'
                ],
                'changelog' => 'v1.1: Agregado seguimiento de progreso, rutinas personalizables y recordatorios inteligentes. Mejorados los consejos y motivación.',
            ],
            [
                'template_id' => 'meditacion_mindfulness',
                'name' => 'Meditación y Mindfulness',
                'description' => 'Desarrolla una práctica de meditación para reducir el estrés y mejorar tu bienestar mental. Ahora con técnicas avanzadas.',
                'categoria' => 'bienestar',
                'version' => '1.2',
                'duration_days' => 21,
                'difficulty_level' => 'beginner',
                'content' => [
                    'goals' => ['Reducir el estrés', 'Mejorar la concentración', 'Aumentar la autoconciencia', 'Desarrollar compasión'],
                    'benefits' => ['Mayor calma mental', 'Mejor manejo emocional', 'Claridad mental', 'Relaciones más saludables'],
                    'techniques' => ['Respiración consciente', 'Escaneo corporal', 'Meditación caminando', 'Meditación de compasión', 'Mindfulness en actividades diarias']
                ],
                'steps' => [
                    'Encuentra un lugar tranquilo',
                    'Adopta una postura cómoda',
                    'Elige tu técnica del día',
                    'Enfócate en el momento presente',
                    'Observa tus pensamientos sin juzgar',
                    'Termina gradualmente con gratitud'
                ],
                'tips' => [
                    'Empieza con 5-10 minutos diarios',
                    'No busques "vaciar" la mente',
                    'La práctica regular es clave',
                    'Experimenta con diferentes técnicas',
                    'Sé paciente contigo mismo'
                ],
                'motivational_quotes' => [
                    'La paz viene de dentro. No la busques fuera.',
                    'Meditar no es huir de la realidad, es encontrar la paz en ella.',
                    'Un momento de tranquilidad puede cambiar todo tu día.',
                    'La compasión hacia ti mismo es el primer paso hacia la paz interior.'
                ],
                'changelog' => 'v1.2: Agregadas técnicas avanzadas de meditación, enfoque en compasión y mindfulness en actividades diarias. Mejorada la progresión de dificultad.',
            ]
        ];

        foreach ($updatedTemplates as $template) {
            HabitTemplate::create($template);
        }
    }
}
