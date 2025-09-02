<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HabitSuggestion;

class HabitSuggestionsSeederPart3 extends Seeder
{
    public function run()
    {
        $habits = [
            // Más hábitos de SALUD
            [
                'name' => 'Comer 5 porciones de frutas y verduras',
                'description' => 'Consumir al menos 5 porciones de frutas y verduras frescas diariamente',
                'icon' => '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 40px; height: 40px;" loop autoplay></lottie-player>',
                'categoria' => 'salud',
                'popularity' => 115,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor energía, mejor digestión, sistema inmune fuerte, prevención de enfermedades',
                'steps' => [
                    'Planificar comidas incluyendo frutas y verduras',
                    'Preparar snacks saludables (frutas cortadas, vegetales)',
                    'Incluir ensalada en almuerzo y cena',
                    'Usar frutas como postre natural',
                    'Hacer smoothies con vegetales verdes'
                ]
            ],
            [
                'name' => 'Tomar vitaminas diarias',
                'description' => 'Consumir suplementos vitamínicos esenciales según necesidades personales',
                'icon' => '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 40px; height: 40px;" loop autoplay></lottie-player>',
                'categoria' => 'salud',
                'popularity' => 90,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Complemento nutricional, mejor energía, sistema inmune fortalecido',
                'steps' => [
                    'Consultar con médico sobre necesidades específicas',
                    'Elegir vitaminas de calidad apropiadas',
                    'Establecer horario fijo para tomarlas',
                    'Asociar con comida para mejor absorción',
                    'Monitorear efectos y ajustar si es necesario'
                ]
            ],
            [
                'name' => 'Hacer ejercicios de respiración',
                'description' => 'Practicar técnicas de respiración profunda por 5-10 minutos diarios',
                'icon' => '<lottie-player src="/animations/meditation.json" background="transparent" speed="1" style="width: 40px; height: 40px;" loop autoplay></lottie-player>',
                'categoria' => 'salud',
                'popularity' => 85,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Reducción del estrés, mejor oxigenación, relajación, control de ansiedad',
                'steps' => [
                    'Encontrar lugar tranquilo para practicar',
                    'Sentarse cómodamente con espalda recta',
                    'Inhalar profundamente por 4 segundos',
                    'Mantener respiración por 4 segundos',
                    'Exhalar lentamente por 6-8 segundos'
                ]
            ],

            // Más hábitos de PRODUCTIVIDAD
            [
                'name' => 'Batch similar tasks',
                'description' => 'Agrupar tareas similares y realizarlas en bloques de tiempo dedicados',
                'icon' => '<i class="fas fa-boxes text-blue-500"></i>',
                'categoria' => 'productividad',
                'popularity' => 80,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mayor eficiencia, menos cambios de contexto, mejor concentración, tiempo optimizado',
                'steps' => [
                    'Identificar tareas similares en lista de pendientes',
                    'Agrupar por tipo: emails, llamadas, revisiones',
                    'Asignar bloques de tiempo específicos para cada grupo',
                    'Completar todas las tareas del mismo tipo juntas',
                    'Minimizar interrupciones durante cada bloque'
                ]
            ],
            [
                'name' => 'Review semanal de objetivos',
                'description' => 'Dedicar tiempo cada semana a revisar progreso y ajustar objetivos',
                'icon' => '<i class="fas fa-chart-line text-blue-500"></i>',
                'categoria' => 'productividad',
                'popularity' => 95,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Claridad de dirección, ajuste de prioridades, motivación, crecimiento continuo',
                'steps' => [
                    'Elegir día fijo de la semana para review',
                    'Revisar objetivos establecidos al inicio de semana',
                    'Evaluar qué se logró y qué quedó pendiente',
                    'Identificar obstáculos y lecciones aprendidas',
                    'Ajustar plan para la siguiente semana'
                ]
            ],

            // Más hábitos de BIENESTAR
            [
                'name' => 'Practicar mindfulness',
                'description' => 'Dedicar 10 minutos diarios a práctica de atención plena y meditación',
                'icon' => '<i class="fas fa-om text-yellow-500"></i>',
                'categoria' => 'bienestar',
                'popularity' => 120,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Reducción del estrés, mayor claridad mental, mejor control emocional, presencia',
                'steps' => [
                    'Encontrar lugar tranquilo sin distracciones',
                    'Sentarse cómodamente con ojos cerrados',
                    'Concentrarse en la respiración natural',
                    'Observar pensamientos sin juzgar, volver a respiración',
                    'Usar apps como Headspace o Calm si necesitas guía'
                ]
            ],
            [
                'name' => 'Escuchar música relajante',
                'description' => 'Incorporar música calmante en rutina diaria para reducir estrés',
                'icon' => '<i class="fas fa-music text-yellow-500"></i>',
                'categoria' => 'bienestar',
                'popularity' => 85,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Relajación, mejor humor, reducción de cortisol, mejora del sueño',
                'steps' => [
                    'Crear playlist de música relajante',
                    'Incluir géneros: clásica, ambient, naturaleza',
                    'Escuchar durante actividades relajantes',
                    'Usar durante breaks en trabajo',
                    'Incluir en rutina antes de dormir'
                ]
            ],

            // Más hábitos de FINANZAS
            [
                'name' => 'Comparar precios antes de comprar',
                'description' => 'Investigar precios en múltiples lugares antes de realizar compras importantes',
                'icon' => '<i class="fas fa-shopping-cart text-green-600"></i>',
                'categoria' => 'finanzas',
                'popularity' => 90,
                'frequency_suggestions' => ['cuando sea necesario'],
                'benefits' => 'Ahorro significativo, mejores decisiones de compra, consciencia financiera',
                'steps' => [
                    'Definir "compra importante" (>$100 por ejemplo)',
                    'Buscar precios en al menos 3 lugares diferentes',
                    'Usar apps de comparación de precios',
                    'Considerar calidad además del precio',
                    'Esperar 24 horas antes de comprar para reflexionar'
                ]
            ],
            [
                'name' => 'Leer sobre finanzas personales',
                'description' => 'Dedicar tiempo semanal a educación financiera a través de libros, blogs o cursos',
                'icon' => '<i class="fas fa-book-open text-green-600"></i>',
                'categoria' => 'finanzas',
                'popularity' => 75,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Mayor conocimiento financiero, mejores decisiones, crecimiento patrimonial',
                'steps' => [
                    'Elegir fuentes confiables de educación financiera',
                    'Programar 30-60 minutos semanales para estudiar',
                    'Tomar notas de conceptos importantes',
                    'Aplicar inmediatamente lo aprendido',
                    'Compartir conocimientos con familia'
                ]
            ],

            // Más hábitos de RELACIONES
            [
                'name' => 'Dar cumplidos genuinos',
                'description' => 'Ofrecer al menos un cumplido sincero diario a diferentes personas',
                'icon' => '<i class="fas fa-gift text-pink-500"></i>',
                'categoria' => 'relaciones',
                'popularity' => 85,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Relaciones más positivas, mejor ambiente social, aumento de confianza mutua',
                'steps' => [
                    'Observar cualidades positivas en otros',
                    'Ser específico en cumplidos (no genérico)',
                    'Expresar cumplidos de manera sincera',
                    'Incluir tanto aspectos personales como profesionales',
                    'Notar el impacto positivo en las personas'
                ]
            ],
            [
                'name' => 'Organizar encuentros sociales',
                'description' => 'Planificar y organizar actividades sociales regulares con amigos y familia',
                'icon' => '<i class="fas fa-users text-pink-500"></i>',
                'categoria' => 'relaciones',
                'popularity' => 70,
                'frequency_suggestions' => ['mensual'],
                'benefits' => 'Red social más fuerte, mejores relaciones, momentos memorables, apoyo social',
                'steps' => [
                    'Identificar personas importantes para incluir',
                    'Planificar actividades que disfruten todos',
                    'Enviar invitaciones con tiempo suficiente',
                    'Considerar diferentes tipos de encuentros (cena, actividad, etc.)',
                    'Hacer seguimiento para mantener conexiones'
                ]
            ],

            // Más hábitos de APRENDIZAJE
            [
                'name' => 'Leer artículos de industria',
                'description' => 'Mantenerse actualizado leyendo artículos relevantes del campo profesional',
                'icon' => '<i class="fas fa-newspaper text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 80,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Conocimiento actualizado, ventaja competitiva, networking, innovación',
                'steps' => [
                    'Identificar fuentes confiables de tu industria',
                    'Suscribirse a newsletters relevantes',
                    'Dedicar 15-20 minutos diarios a lectura',
                    'Tomar notas de insights importantes',
                    'Compartir artículos interesantes con colegas'
                ]
            ],
            [
                'name' => 'Enseñar lo aprendido',
                'description' => 'Compartir conocimientos con otros para reforzar el propio aprendizaje',
                'icon' => '<i class="fas fa-chalkboard-teacher text-green-500"></i>',
                'categoria' => 'aprendizaje',
                'popularity' => 75,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Aprendizaje profundizado, mejores habilidades de comunicación, networking, satisfacción personal',
                'steps' => [
                    'Identificar temas que conoces bien',
                    'Encontrar audiencia apropiada (colegas, amigos, online)',
                    'Preparar explicaciones claras y ejemplos',
                    'Usar diferentes medios: conversación, blog, presentación',
                    'Recibir feedback para mejorar enseñanza'
                ]
            ]
        ];

        foreach ($habits as $habit) {
            HabitSuggestion::create($habit);
        }

        $this->command->info('Se han creado ' . count($habits) . ' sugerencias de hábitos finales (Parte 3)');
    }
}
