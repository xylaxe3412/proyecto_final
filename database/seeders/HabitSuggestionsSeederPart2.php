<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HabitSuggestion;

class HabitSuggestionsSeederPart2 extends Seeder
{
    public function run()
    {
        $habits = [
            // Más hábitos de SALUD
            [
                'name' => 'Tomar 8 vasos de agua al día',
                'description' => 'Mantenerse correctamente hidratado bebiendo al menos 2 litros de agua diariamente',
                'icon' => '<i class="fas fa-tint text-blue-500"></i>',
                'categoria' => 'salud',
                'popularity' => 120,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor digestión, piel más saludable, mayor energía, eliminación de toxinas',
                'steps' => [
                    'Comenzar el día tomando un vaso de agua al despertar',
                    'Llevar una botella de agua siempre contigo',
                    'Establecer recordatorios cada 2 horas para beber agua',
                    'Beber un vaso de agua antes de cada comida',
                    'Usar apps para tracking de consumo de agua'
                ]
            ],
            [
                'name' => 'Hacer estiramientos matutinos',
                'description' => 'Rutina de estiramientos de 10-15 minutos cada mañana para activar el cuerpo',
                'icon' => '<i class="fas fa-om text-red-500"></i>',
                'categoria' => 'salud',
                'popularity' => 105,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor flexibilidad, reducción de tensiones, mejor circulación, preparación para el día',
                'steps' => [
                    'Levantarse 15 minutos más temprano',
                    'Hacer estiramientos de cuello y hombros',
                    'Estirar brazos y espalda',
                    'Estiramientos de piernas y caderas',
                    'Terminar con respiraciones profundas'
                ]
            ],
            [
                'name' => 'Caminar 30 minutos diarios',
                'description' => 'Incorporar una caminata de media hora en la rutina diaria para mantenerse activo',
                'icon' => '<i class="fas fa-walking text-red-500"></i>',
                'categoria' => 'salud',
                'popularity' => 130,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora cardiovascular, control de peso, reducción del estrés, vitamina D',
                'steps' => [
                    'Elegir un momento fijo del día (mañana, tarde o noche)',
                    'Planificar una ruta de 30 minutos cerca de casa',
                    'Usar ropa y calzado cómodo',
                    'Comenzar con 15 minutos si es principiante',
                    'Incrementar gradualmente hasta 30 minutos'
                ]
            ],
            [
                'name' => 'Dormir 7-8 horas diarias',
                'description' => 'Establecer horarios de sueño consistentes para obtener descanso reparador',
                'icon' => '<i class="fas fa-bed text-red-500"></i>',
                'categoria' => 'salud',
                'popularity' => 140,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor concentración, sistema inmune fuerte, regulación hormonal, recuperación muscular',
                'steps' => [
                    'Establecer hora fija para acostarse',
                    'Crear rutina relajante antes de dormir',
                    'Evitar pantallas 1 hora antes de dormir',
                    'Mantener habitación fresca y oscura',
                    'Despertar a la misma hora todos los días'
                ]
            ],

            // Más hábitos de PRODUCTIVIDAD
            [
                'name' => 'Planificar el día siguiente',
                'description' => 'Dedicar 10 minutos cada noche a planificar las tareas del día siguiente',
                'icon' => '<i class="fas fa-calendar-alt text-blue-500"></i>',
                'categoria' => 'productividad',
                'popularity' => 115,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor organización, menos estrés matutino, objetivos claros, mejor gestión del tiempo',
                'steps' => [
                    'Revisar agenda y compromisos del día siguiente',
                    'Listar 3 tareas prioritarias',
                    'Estimular tiempo necesario para cada tarea',
                    'Preparar materiales necesarios',
                    'Visualizar el día exitoso'
                ]
            ],
            [
                'name' => 'Aplicar técnica Pomodoro',
                'description' => 'Trabajar en bloques de 25 minutos con descansos de 5 minutos',
                'icon' => '<i class="fas fa-clock text-blue-500"></i>',
                'categoria' => 'productividad',
                'popularity' => 100,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor concentración, menos procrastinación, mejor gestión del tiempo, reducción de fatiga',
                'steps' => [
                    'Elegir una tarea específica',
                    'Configurar timer para 25 minutos',
                    'Trabajar sin distracciones hasta que suene',
                    'Tomar descanso de 5 minutos',
                    'Repetir 4 ciclos, luego descanso largo de 15-30 min'
                ]
            ],
            [
                'name' => 'Inbox Zero email',
                'description' => 'Mantener bandeja de entrada de email vacía procesando todos los mensajes diariamente',
                'icon' => '<i class="fas fa-envelope text-blue-500"></i>',
                'categoria' => 'productividad',
                'popularity' => 85,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Menos estrés, mayor organización, comunicación más efectiva, control mental',
                'steps' => [
                    'Revisar email 2-3 veces por día máximo',
                    'Aplicar regla de 2 minutos: si toma menos de 2 min, hacerlo ahora',
                    'Para emails más largos: programar, delegar o archivar',
                    'Usar carpetas o etiquetas para organizar',
                    'Cancelar suscripciones innecesarias'
                ]
            ],

            // Más hábitos de BIENESTAR
            [
                'name' => 'Practicar gratitud diaria',
                'description' => 'Escribir 3 cosas por las que te sientes agradecido cada día',
                'icon' => '<i class="fas fa-praying-hands text-yellow-500"></i>',
                'categoria' => 'bienestar',
                'popularity' => 125,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor felicidad, mejor perspectiva, reducción del estrés, relaciones mejoradas',
                'steps' => [
                    'Elegir momento del día (mañana o noche)',
                    'Tener libreta dedicada o app de gratitud',
                    'Escribir 3 cosas específicas de agradecimiento',
                    'Incluir por qué te sientes agradecido por cada una',
                    'Releer entradas anteriores semanalmente'
                ]
            ],
            [
                'name' => 'Desconexión digital nocturna',
                'description' => 'Apagar dispositivos electrónicos 1 hora antes de dormir',
                'icon' => '<i class="fas fa-mobile-alt text-yellow-500"></i>',
                'categoria' => 'bienestar',
                'popularity' => 110,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor calidad de sueño, reducción del estrés, más tiempo para relax, mejor relaciones',
                'steps' => [
                    'Establecer horario fijo para apagar dispositivos',
                    'Usar modo avión o dejar dispositivos fuera del dormitorio',
                    'Reemplazar scroll con actividades relajantes',
                    'Leer libro físico o hacer journaling',
                    'Comunicar límites a familia y amigos'
                ]
            ],
            [
                'name' => 'Tiempo en la naturaleza',
                'description' => 'Pasar al menos 20 minutos diarios en contacto con la naturaleza',
                'icon' => '<i class="fas fa-tree text-yellow-500"></i>',
                'categoria' => 'bienestar',
                'popularity' => 95,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Reducción del estrés, mejor humor, vitamina D, conexión con el ambiente',
                'steps' => [
                    'Identificar espacios verdes cercanos (parques, jardines)',
                    'Integrar en rutina: camino al trabajo o descansos',
                    'Practicar mindfulness mientras estás en la naturaleza',
                    'Observar plantas, árboles, cielo',
                    'Respirar aire fresco conscientemente'
                ]
            ],

            // Más hábitos de FINANZAS
            [
                'name' => 'Ahorrar automáticamente',
                'description' => 'Configurar transferencia automática mensual a cuenta de ahorros',
                'icon' => '<i class="fas fa-university text-green-600"></i>',
                'categoria' => 'finanzas',
                'popularity' => 105,
                'frequency_suggestions' => ['mensual'],
                'benefits' => 'Crecimiento del patrimonio, seguridad financiera, disciplina automática',
                'steps' => [
                    'Analizar ingresos y gastos mensuales',
                    'Determinar cantidad fija para ahorrar (10-20% ingresos)',
                    'Configurar transferencia automática al inicio del mes',
                    'Abrir cuenta de ahorros separada si es necesario',
                    'Revisar y ajustar cantidad trimestralmente'
                ]
            ],
            [
                'name' => 'Negociar gastos recurrentes',
                'description' => 'Revisar y negociar gastos fijos mensuales para obtener mejores tarifas',
                'icon' => '<i class="fas fa-phone text-green-600"></i>',
                'categoria' => 'finanzas',
                'popularity' => 70,
                'frequency_suggestions' => ['trimestral'],
                'benefits' => 'Reducción de gastos, mejor gestión financiera, ahorros significativos',
                'steps' => [
                    'Listar todos los gastos recurrentes (internet, seguros, suscripciones)',
                    'Investigar ofertas de la competencia',
                    'Llamar a proveedores actuales para negociar',
                    'Considerar cambio de proveedor si es necesario',
                    'Documentar ahorros obtenidos'
                ]
            ],

            // Más hábitos de RELACIONES
            [
                'name' => 'Llamar a un ser querido',
                'description' => 'Contactar regularmente con familia o amigos para mantener vínculos',
                'icon' => '<i class="fas fa-phone text-pink-500"></i>',
                'categoria' => 'relaciones',
                'popularity' => 90,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Relaciones más fuertes, red de apoyo, bienestar emocional, conexión social',
                'steps' => [
                    'Crear lista de personas importantes para contactar',
                    'Programar llamadas en calendario',
                    'Preparar preguntas sobre su vida actual',
                    'Escuchar activamente durante la conversación',
                    'Hacer seguimiento de temas importantes mencionados'
                ]
            ],
            [
                'name' => 'Escribir notas de agradecimiento',
                'description' => 'Enviar mensajes de agradecimiento a personas que han ayudado',
                'icon' => '<i class="fas fa-heart text-pink-500"></i>',
                'categoria' => 'relaciones',
                'popularity' => 75,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Relaciones fortalecidas, mayor positividad, red profesional, reciprocidad',
                'steps' => [
                    'Identificar personas que han ayudado recientemente',
                    'Ser específico sobre qué agradeces',
                    'Escribir mensaje personalizado (no genérico)',
                    'Elegir medio apropiado (email, carta, mensaje)',
                    'Incluir cómo su ayuda te ha impactado'
                ]
            ],

            // Más hábitos de APRENDIZAJE
            [
                'name' => 'Escuchar podcasts educativos',
                'description' => 'Dedicar tiempo diario a podcasts de desarrollo personal o profesional',
                'icon' => '<i class="fas fa-headphones text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 100,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Aprendizaje continuo, nuevas perspectivas, uso eficiente del tiempo, desarrollo profesional',
                'steps' => [
                    'Identificar temas de interés personal/profesional',
                    'Buscar podcasts recomendados en esas áreas',
                    'Descargar apps de podcasts (Spotify, Apple Podcasts)',
                    'Aprovechar tiempos muertos (commute, ejercicio, tareas domésticas)',
                    'Tomar notas de insights importantes'
                ]
            ],
            [
                'name' => 'Aprender nuevas palabras',
                'description' => 'Estudiar y usar 1-2 palabras nuevas cada día para expandir vocabulario',
                'icon' => '<i class="fas fa-book text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 80,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor comunicación, mayor confianza, desarrollo cognitivo, expresión más rica',
                'steps' => [
                    'Usar app de vocabulario o diccionario diario',
                    'Anotar palabras nuevas encontradas en lecturas',
                    'Buscar definición, sinónimos y antónimos',
                    'Crear oraciones con las palabras nuevas',
                    'Intentar usar las palabras en conversaciones del día'
                ]
            ],
            [
                'name' => 'Hacer un curso online',
                'description' => 'Dedicar 30 minutos diarios a completar cursos online de habilidades relevantes',
                'icon' => '<i class="fas fa-laptop text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 95,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Desarrollo de habilidades, ventaja competitiva, adaptación al cambio, crecimiento personal',
                'steps' => [
                    'Identificar habilidades necesarias para objetivos',
                    'Elegir plataforma (Coursera, Udemy, LinkedIn Learning)',
                    'Seleccionar curso con buenas evaluaciones',
                    'Programar tiempo fijo diario para estudio',
                    'Aplicar inmediatamente lo aprendido en proyectos'
                ]
            ],
            [
                'name' => 'Escribir reflexiones diarias',
                'description' => 'Dedicar 10 minutos cada día a escribir reflexiones sobre experiencias y aprendizajes',
                'icon' => '<i class="fas fa-pen text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 85,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Autoconocimiento, procesamiento de experiencias, claridad mental, desarrollo de insight',
                'steps' => [
                    'Elegir momento fijo (mañana o noche)',
                    'Tener libreta dedicada o documento digital',
                    'Escribir sobre experiencias del día',
                    'Incluir qué aprendiste o cómo creciste',
                    'Hacer preguntas reflexivas sobre decisiones tomadas'
                ]
            ]
        ];

        foreach ($habits as $habit) {
            HabitSuggestion::create($habit);
        }

        $this->command->info('Se han creado ' . count($habits) . ' sugerencias de hábitos adicionales (Parte 2)');
    }
}
