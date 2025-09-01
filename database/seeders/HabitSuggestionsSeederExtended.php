<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HabitSuggestion;

class HabitSuggestionsSeederExtended extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suggestions = [
            // SALUD (15 hábitos)
            [
                'name' => 'Ejercicio matutino',
                'description' => 'Hacer ejercicio todas las mañanas para mantenerme en forma y energizado',
                'icon' => '<i class="fas fa-running text-red-500"></i>',
                'categoria' => 'salud',
                'popularity' => 190,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Aumenta energía, mejora estado de ánimo, fortalece el cuerpo',
                'steps' => [
                    'SEMANA 1-2: Comenzar con solo 10 minutos de ejercicio suave (caminar, estiramientos). Establecer alarma 15 minutos antes de la hora usual de despertar.',
                    'PREPARACIÓN NOCTURNA: Cada noche, colocar ropa deportiva, zapatos y botella de agua junto a la cama para eliminar barreras matutinas.',
                    'RUTINA DE ACTIVACIÓN: Al despertar, beber un vaso de agua, hacer 5 respiraciones profundas y ponerse la ropa deportiva inmediatamente (sin pensar).',
                    'SEMANA 3-4: Incrementar gradualmente a 15-20 minutos. Agregar ejercicios de calentamiento: rotaciones de brazos, movimientos articulares suaves.',
                    'SISTEMA DE RECOMPENSAS: Después del ejercicio, disfrutar de un desayuno especial o café favorito. Marcar en calendario cada día completado.',
                    'SEMANA 5+: Evolucionar a rutina completa de 30 minutos con cardio y fuerza. Crear playlist motivacional que solo escuches durante el ejercicio.',
                    'SEGUIMIENTO DE PROGRESO: Usar app fitness o diario para registrar tiempo, intensidad y cómo te sientes después. Celebrar cada semana completada.'
                ]
            ],
            [
                'name' => 'Beber 8 vasos de agua',
                'description' => 'Mantener una hidratación óptima bebiendo suficiente agua durante el día',
                'icon' => '<i class="fas fa-tint text-blue-500"></i>',
                'categoria' => 'salud',
                'popularity' => 200,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora digestión, piel radiante, más energía, mejor concentración',
                'steps' => [
                    'CONFIGURACIÓN INICIAL: Comprar botella de 1 litro con marcas de tiempo o usar app de hidratación. Establecer meta inicial de 6 vasos si 8 parece mucho.',
                    'ANCLAJE MATUTINO: Colocar vaso de agua junto a la cama. Al despertar, beber 1-2 vasos ANTES de revisar teléfono o levantarse.',
                    'MÉTODO DE RECORDATORIOS: Configurar alarmas cada 2 horas con mensajes personalizados ("¡Hora de nutrir tu cuerpo!" "¡Tu piel te lo agradecerá!").',
                    'TÉCNICA DE APILAMIENTO: Asociar cada vaso con actividades existentes: antes de cada comida, después de ir al baño, al llegar a la oficina.',
                    'HACER ATRACTIVO: Agregar limón, pepino o menta al agua. Usar botellas bonitas. Crear ritual de "agua especial" para momentos específicos.',
                    'SISTEMA VISUAL: Usar 8 ligas en la muñeca, mover una a la otra mano por cada vaso bebido. O app con gráficos motivacionales.',
                    'REVISIÓN NOCTURNA: Cada noche evaluar cuánto bebiste. Si faltó, identificar qué momento del día fue difícil y planear estrategia para mañana.'
                ]
            ],
            [
                'name' => 'Dormir 8 horas',
                'description' => 'Establecer un horario de sueño regular para una recuperación óptima',
                'icon' => '<i class="fas fa-bed text-red-500"></i>',
                'categoria' => 'salud',
                'popularity' => 180,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor concentración, sistema inmune fuerte, estado de ánimo estable',
                'steps' => [
                    'CÁLCULO PERSONALIZADO: Determinar hora exacta de despertar y trabajar hacia atrás. Si despiertas a 6am, hora de dormir debe ser 10pm (incluye 30 min para conciliar).',
                    'RITUAL DE DESCONEXIÓN (2 horas antes): Establecer "hora de oro" - sin pantallas, luces tenues, actividades relajantes. Crear checklist visual.',
                    'AMBIENTE OPTIMIZADO: Habitación 18-20°C, cortinas blackout, tapones para oídos si necesario. Eliminar dispositivos electrónicos del dormitorio.',
                    'RUTINA PRE-SUEÑO (30 min): Secuencia fija cada noche: ducha tibia, té de manzanilla, 5 min de lecturas ligeras o meditación guiada.',
                    'TÉCNICA 4-7-8: Si cuesta conciliar el sueño, respirar por nariz 4 segundos, mantener 7 segundos, exhalar por boca 8 segundos. Repetir 4 ciclos.',
                    'CONSISTENCIA DE FINES DE SEMANA: Mantener mismos horarios ±30 minutos incluso los fines de semana para no alterar el ritmo circadiano.',
                    'SEGUIMIENTO Y AJUSTE: Usar app de sueño o diario. Registrar calidad del sueño, tiempo para dormirse, despertar nocturno. Ajustar rutina según patrones.'
                ]
            ],
            [
                'name' => 'Caminar 10,000 pasos',
                'description' => 'Caminar diariamente para mantener un estilo de vida activo',
                'icon' => '🚶‍♂️',
                'categoria' => 'salud',
                'popularity' => 160,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora salud cardiovascular, fortalece piernas, reduce estrés',
                'steps' => [
                    'BASELINE Y META GRADUAL: Primera semana, contar pasos actuales sin cambiar rutina. Establecer meta inicial de +1000 pasos del promedio actual.',
                    'CONFIGURACIÓN DE HERRAMIENTAS: Instalar app contador de pasos o usar smartwatch. Configurar recordatorios cada 2 horas para moverse.',
                    'INTEGRACIÓN EN RUTINA DIARIA: Aparcar más lejos, subir escaleras, bajarse una parada antes del destino. Hacer llamadas telefónicas caminando.',
                    'CAMINATA ANCHOR: Programar caminata de 20-30 min a hora fija (después del almuerzo/antes de cenar). Crear playlist específica para este momento.',
                    'MÉTODO 2-MINUTOS: Cada hora, caminar 2 minutos aunque sea en casa/oficina. Configurar alarma con mensaje "pausa activa". Acumula 1000+ pasos diarios.',
                    'SOCIAL Y MOTIVACIONAL: Invitar amigo/familia a caminar juntos 2-3 veces por semana. Compartir progreso en grupo de apoyo o redes sociales.',
                    'SEGUIMIENTO SEMANAL: Revisar promedio semanal de pasos. Celebrar cada semana que alcances meta. Incrementar gradualmente 500 pasos cada 2 semanas.'
                ]
            ],
            [
                'name' => 'Comer 5 frutas y verduras',
                'description' => 'Incluir suficientes frutas y verduras en la alimentación diaria',
                'icon' => '🥗',
                'categoria' => 'salud',
                'popularity' => 140,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Más vitaminas, mejor digestión, sistema inmune fuerte',
                'steps' => [
                    'PLANIFICACIÓN SEMANAL: Cada domingo, planificar qué frutas/verduras comer durante la semana. Hacer lista de compras específica con 7 frutas y 10 verduras.',
                    'PREPARACIÓN BATCH: Domingos, lavar y cortar frutas para toda la semana. Preparar tuppers con verduras listas para cocinar. Elimina excusas de preparación.',
                    'REGLA 2-1-2: 2 frutas en desayuno/snacks, 1 en almuerzo, 2 verduras en almuerzo y cena. Crear estructura clara para cada comida.',
                    'SUSTITUCIÓN INTELIGENTE: Reemplazar snacks procesados por fruta. Cambiar acompañamientos por ensaladas. Agregar verduras a comidas existentes.',
                    'SMOOTHIES ESTRATÉGICOS: Crear smoothie matutino con 2 frutas + 1 verdura (espinaca/kale). Preparar ingredientes congelados en bolsas individuales.',
                    'SISTEMA VISUAL DE CONTEO: Usar 5 fichas/piedritas, mover una por cada porción consumida. O app con fotos para registrar cada fruta/verdura.',
                    'EXPERIMENTACIÓN SEMANAL: Cada semana probar 1 fruta y 1 verdura nueva. Buscar recetas creativas. Hacer divertido el proceso de incorporar variedad.'
                ]
            ],
            [
                'name' => 'Estiramientos matutinos',
                'description' => 'Realizar rutina de estiramientos al despertar para activar el cuerpo',
                'icon' => '🤸‍♀️',
                'categoria' => 'salud',
                'popularity' => 120,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora flexibilidad, reduce tensión muscular, energiza el cuerpo',
                'steps' => [
                    'RUTINA PROGRESIVA: Semana 1-2: solo 5 min de estiramientos básicos en la cama. Semana 3-4: 10 min de pie. Semana 5+: rutina completa de 15 min.',
                    'SECUENCIA FIJA MEMORIZABLE: Crear rutina de 8-10 estiramientos específicos en orden fijo. Practicar hasta poder hacerla sin pensar (automatización).',
                    'ACTIVADOR INMEDIATO: Inmediatamente al despertar, antes de revisar teléfono, hacer primeros 3 estiramientos en la cama (cuello, brazos, piernas).',
                    'VIDEO GUÍA PERSONALIZADO: Grabar video propio de la rutina o usar app específica. Mismo video cada día para crear consistencia y familiaridad.',
                    'CONEXIÓN RESPIRACIÓN-MOVIMIENTO: Coordinar cada estiramiento con respiración: inhalar al extender, exhalar al relajar. Hace la rutina meditativa.',
                    'TRACKING DE SENSACIONES: Registrar en escala 1-10 cómo se siente el cuerpo antes y después. Notar mejoras en flexibilidad y rigidez matutina.',
                    'EVOLUCIÓN MENSUAL: Cada mes, agregar un nuevo estiramiento o aumentar duración. Tomar fotos de flexibilidad para ver progreso visual tangible.'
                ]
            ],
            [
                'name' => 'Tomar vitaminas',
                'description' => 'Tomar suplementos vitamínicos recomendados diariamente',
                'icon' => '💊',
                'categoria' => 'salud',
                'popularity' => 100,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Complementa nutrición, fortalece sistema inmune',
                'steps' => [
                    'CONSULTA PROFESIONAL: Antes de comenzar, consultar médico/nutricionista para determinar vitaminas específicas necesarias según análisis de sangre.',
                    'ORGANIZACIÓN SEMANAL: Usar pastillero de 7 días, llenar cada domingo. Colocar junto al cepillo de dientes o cafetera para crear asociación visual.',
                    'ANCLAJE CON RUTINA EXISTENTE: Tomar vitaminas siempre con misma comida (desayuno recomendado). Crear regla: "No café hasta tomar vitaminas".',
                    'SISTEMA DE RECORDATORIOS MÚLTIPLES: Alarma en teléfono + nota adhesiva en espejo + pastillero visible. Múltiples señales previenen olvidos.',
                    'REGISTRO DE CONSISTENCIA: Usar app o calendario para marcar cada día completado. Meta inicial: 6 de 7 días por semana (80% consistencia).',
                    'MANEJO DE OLVIDOS: Si olvidas una mañana, tomar con almuerzo (no duplicar). Analizar por qué se olvidó y ajustar sistema de recordatorios.',
                    'EVALUACIÓN MENSUAL: Cada mes, evaluar si sientes diferencias en energía/salud. Discutir con médico efectividad y ajustar dosis o tipos según necesidad.'
                ]
            ],

            // PRODUCTIVIDAD (15 hábitos)
            [
                'name' => 'Planificar el día',
                'description' => 'Dedicar tiempo cada mañana a organizar y priorizar las tareas del día',
                'icon' => '📋',
                'categoria' => 'productividad',
                'popularity' => 170,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Mayor eficiencia, menos estrés, mejor gestión del tiempo',
                'steps' => [
                    'TIEMPO SAGRADO MATUTINO: Reservar primeros 10-15 min del día (antes de emails/distracciones) exclusivamente para planificación. Defender este tiempo.',
                    'MÉTODO 3-2-1: Elegir 3 tareas importantes, 2 tareas medianas, 1 tarea pequeña. Escribir en orden de prioridad específica para evitar decisiones durante el día.',
                    'ESTIMACIÓN REALISTA DE TIEMPO: Para cada tarea, estimar tiempo necesario + 25% buffer. Aprender de errores de estimación para mejorar precisión.',
                    'BLOQUES DE TIEMPO ESPECÍFICOS: Asignar horarios específicos a cada tarea en calendario. "9:00-10:30 Proyecto X", "14:00-15:00 Emails". Tratar como citas ineludibles.',
                    'IDENTIFICACIÓN DE ENERGÍA PERSONAL: Reconocer cuándo tienes más energía (mañana/tarde) y programar tareas difíciles en esos momentos.',
                    'SISTEMA DE REVISIÓN NOCTURNA: Cada noche, revisar qué se completó, qué quedó pendiente y por qué. Ajustar estimaciones y planificación para día siguiente.',
                    'HERRAMIENTAS CONSISTENTES: Usar siempre la misma app/libreta para planificar. Crear plantilla o formato fijo para reducir decisiones y acelerar proceso.'
                ]
            ],
            [
                'name' => 'Técnica Pomodoro',
                'description' => 'Trabajar en bloques de tiempo concentrado con descansos regulares',
                'icon' => '🍅',
                'categoria' => 'productividad',
                'popularity' => 150,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Mayor concentración, menos fatiga mental, mejor calidad de trabajo',
                'steps' => [
                    'CONFIGURACIÓN DE ENTORNO: Eliminar distracciones del espacio de trabajo (teléfono en modo avión, cerrar pestañas innecesarias, avisar que no molesten).',
                    'ELECCIÓN DE TAREA ESPECÍFICA: Antes de iniciar timer, definir exactamente qué se hará en esos 25 min. Una sola tarea por pomodoro para mantener foco.',
                    'TIMER SAGRADO: Usar timer dedicado (no teléfono para evitar distracciones). Los 25 min son inviolables - no parar por nada que no sea emergencia real.',
                    'DESCANSO ACTIVO: En los 5 min de descanso, levantarse, caminar, estirar, hidratarse. NO revisar redes sociales ni emails (estimulación mental).',
                    'REGISTRO DE POMODOROS: Anotar cuántos pomodoros completos por día. Meta inicial: 4 pomodoros diarios (2 horas de trabajo concentrado).',
                    'MANEJO DE INTERRUPCIONES: Si surge algo "urgente", anotarlo en papel y continuar. Si es verdadera emergencia, pausar timer y reiniciar después.',
                    'DESCANSO LARGO ESTRATÉGICO: Cada 4 pomodoros, tomar descanso de 15-30 min. Usar para recargar energía mental con actividad completamente diferente.'
                ]
            ],
            [
                'name' => 'Revisar emails 3 veces',
                'description' => 'Limitar la revisión de correos a momentos específicos para evitar distracciones',
                'icon' => '📧',
                'categoria' => 'productividad',
                'popularity' => 120,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Menos distracciones, mayor concentración en tareas importantes',
                'steps' => [
                    'HORARIOS FIJOS NO NEGOCIABLES: Establecer 3 horarios específicos (ej: 9:00, 13:00, 17:00). Comunicar a equipo estos horarios para alineación de expectativas.',
                    'RESISTENCIA A LA TENTACIÓN: Mantener notificaciones de email desactivadas. Usar apps de bloqueo si es necesario. Crear física distancia con el teléfono.',
                    'SISTEMA TRIAJE RÁPIDO: Al abrir emails, clasificar inmediatamente: HACER (responder ahora), DELEGAR, AGENDAR (para más tarde), ARCHIVAR.',
                    'REGLA DE 2 MINUTOS: Si email se puede responder en menos de 2 min, hacerlo inmediatamente. Si requiere más tiempo, agendarlo para bloque específico.',
                    'RESPUESTAS BATCH: Durante sesión de emails, responder todos los pendientes de una vez. Escribir respuestas concisas y directas para ahorrar tiempo.',
                    'COMUNICACIÓN DE DISPONIBILIDAD: Usar firma automática indicando horarios de respuesta. Establecer expectativas claras con clientes/colegas.',
                    'URGENCIAS VERDADERAS: Crear protocolo alternativo para urgencias reales (WhatsApp, llamada). Educar a contactos sobre qué constituye verdadera urgencia.'
                ]
            ],
            [
                'name' => 'Organizar escritorio',
                'description' => 'Mantener el espacio de trabajo limpio y organizado',
                'icon' => '🗂️',
                'categoria' => 'productividad',
                'popularity' => 110,
                'frequency_suggestions' => ['diario', 'días laborales'],
                'benefits' => 'Reduce distracciones, mejora concentración, aumenta eficiencia',
                'steps' => [
                    'RITUAL DE CIERRE DIARIO: Cada final de jornada, dedicar 5-10 min a limpiar escritorio completamente. Solo elementos del día siguiente permanecen.',
                    'PRINCIPIO "UN LUGAR PARA CADA COSA": Asignar ubicación específica a cada objeto (bolígrafos, documentos, dispositivos). Entrenar cerebro para automatizar orden.',
                    'REGLA DE SUPERFICIE DESPEJADA: Escritorio debe tener solo elementos de tarea actual. Todo lo demás va a cajones, estantes o ubicaciones designadas.',
                    'SISTEMA DE BANDEJAS/ORGANIZADORES: Usar bandejas para "ENTRADA", "EN PROCESO", "SALIDA". Documentos fluyen por sistema organizado, no se acumulan.',
                    'GESTIÓN DIGITAL PARALELA: Organizar escritorio de computadora igual que físico. Carpetas claras, desktop limpio, archivos en ubicaciones lógicas.',
                    'REVISIÓN SEMANAL PROFUNDA: Domingos, hacer limpieza más profunda. Desechar innecesario, reorganizar sistemas, reabastecer suministros.',
                    'PERSONALIZACIÓN MOTIVADORA: Agregar 1-2 elementos personales que motiven (planta, foto, objeto inspirador) pero sin crear desorden visual.'
                ]
            ],
            [
                'name' => 'Hacer lista de tareas',
                'description' => 'Crear y actualizar lista diaria de tareas pendientes',
                'icon' => '✅',
                'categoria' => 'productividad',
                'popularity' => 140,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor organización, sensación de logro, nada se olvida',
                'steps' => [
                    'SISTEMA HÍBRIDO PAPEL-DIGITAL: Usar libreta física para captura rápida de ideas + app digital para gestión y sincronización entre dispositivos.',
                    'ESTRUCTURA JERÁRQUICA CLARA: Organizar por proyectos > tareas > subtareas. Usar niveles de identación o numeración para jerarquía visual clara.',
                    'PRIORIZACIÓN CON MATRIZ EISENHOWER: Clasificar cada tarea en urgente/importante, importante/no urgente, urgente/no importante, ni urgente/ni importante.',
                    'FECHAS LÍMITE REALISTAS: Asignar deadline específico a cada tarea. Usar fechas buffer - si necesitas algo para viernes, programar para miércoles.',
                    'DESCOMPOSICIÓN DE TAREAS GRANDES: Si tarea toma más de 2 horas, dividir en pasos de 30-60 min. Facilita inicio y genera momentum de completación.',
                    'RITUAL DE REVISIÓN GTD: Revisar lista completa semanalmente. Actualizar prioridades, eliminar obsoletas, agregar nuevas, reorganizar por contexto.',
                    'CELEBRACIÓN DE LOGROS: Al completar tarea, tachar/marcar con satisfacción consciente. Revisar semanalmente todo lo logrado para motivación.'
                ]
            ],

            // BIENESTAR PERSONAL (10 hábitos)
            [
                'name' => 'Meditación diaria',
                'description' => 'Practicar mindfulness o meditación para reducir estrés y mejorar concentración',
                'icon' => '🧘‍♀️',
                'categoria' => 'bienestar',
                'popularity' => 160,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Reduce estrés, mejora concentración, mayor claridad mental',
                'steps' => [
                    'PROGRESIÓN TEMPORAL GRADUAL: Semana 1-2: 5 min diarios. Semana 3-4: 10 min. Mes 2+: 15-20 min. Construir hábito antes que duración.',
                    'ESPACIO SAGRADO CONSISTENTE: Crear rincón específico para meditación con cojín/silla, vela o incienso. Mismo lugar cada día genera asociación mental.',
                    'HORARIO ANCLADO: Meditar siempre a la misma hora (preferible mañana antes de actividades). Vincula con rutina existente como después de levantarse.',
                    'TÉCNICA PROGRESIVA: Comenzar con respiración básica (contar 1-10), luego body scan, después meditación guiada, finalmente silencio total.',
                    'MANEJO DE PENSAMIENTOS SIN JUICIO: Cuando mente divague, simplemente notar "pensando" y regresar suavemente a respiración. No combatir pensamientos.',
                    'APP DE APOYO ESTRUCTURADO: Usar Headspace, Calm o similar para guías diarias. Programas estructurados mantienen motivación y progresión.',
                    'TRACKING DE BENEFICIOS: Registrar nivel de estrés/claridad mental antes y después. Notar mejoras en paciencia, sueño, concentración diaria.'
                ]
            ],
            [
                'name' => 'Diario de gratitud',
                'description' => 'Escribir 3 cosas por las que me siento agradecido cada día',
                'icon' => '🙏',
                'categoria' => 'bienestar',
                'popularity' => 130,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora estado de ánimo, perspectiva positiva, reduce ansiedad',
                'steps' => [
                    'MOMENTO SAGRADO CONSISTENTE: Elegir mismo momento cada día (mañana para empezar positivo o noche para reflexionar). Crear ritual especial.',
                    'HERRAMIENTA DEDICADA: Tener libreta hermosa exclusiva para gratitud o app específica. Que sea especial, no cualquier cuaderno.',
                    'REGLA DE ESPECIFICIDAD: En lugar de "familia", escribir "La sonrisa de mamá cuando le conté mi día" o "El abrazo espontáneo de mi hijo".',
                    'TÉCNICA DEL PORQUÉ: Para cada gratitud, explicar por qué te sientes agradecido. Profundizar en el impacto emocional que tuvo.',
                    'DIVERSIFICACIÓN DE CATEGORÍAS: Rotar entre diferentes áreas: relaciones, logros personales, momentos simples, naturaleza, salud.',
                    'RELECTURA ESTRATÉGICA: Una vez por semana, releer entradas de días difíciles para recordar que siempre hay algo positivo.',
                    'COMPARTIR GRATITUD: Ocasionalmente, compartir una gratitud directamente con la persona involucrada. Amplifica el efecto positivo.'
                ]
            ],
            [
                'name' => 'Respiración profunda',
                'description' => 'Practicar ejercicios de respiración para reducir estrés',
                'icon' => '🌬️',
                'categoria' => 'bienestar',
                'popularity' => 115,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Reduce ansiedad, mejora oxigenación, calma la mente',
                'steps' => [
                    'CONFIGURACIÓN DEL ESPACIO: Encontrar lugar tranquilo, posición cómoda (sentado o acostado), ropa holgada, temperatura agradable.',
                    'TÉCNICA 4-7-8 BÁSICA: Inhalar por nariz 4 seg, retener aire 7 seg, exhalar por boca 8 seg. Empezar con 4 ciclos, aumentar gradualmente.',
                    'HORARIOS ESTRATÉGICOS: Practicar al despertar (energiza), antes de comidas (mejora digestión), antes de dormir (relaja), en momentos de estrés.',
                    'VISUALIZACIÓN COMBINADA: Mientras respiras, imaginar que inhalas calma/energía y exhalas tensión/negatividad. Potencia el efecto.',
                    'APP DE APOYO GUIADO: Usar apps como Calm, Headspace para respiraciones guiadas. Diferentes técnicas según objetivo (energizar/relajar).',
                    'TRACKING DE BENEFICIOS: Registrar nivel de estrés antes y después (escala 1-10). Notar mejoras en sueño, concentración, paciencia.',
                    'RESPIRACIÓN DE EMERGENCIA: Crear protocolo para momentos de alta ansiedad: 10 respiraciones profundas inmediatas, enfoque solo en el aire.'
                ]
            ],

            // FINANZAS (8 hábitos)
            [
                'name' => 'Revisar gastos diarios',
                'description' => 'Registrar y revisar todos los gastos del día para mantener control financiero',
                'icon' => '💰',
                'categoria' => 'finanzas',
                'popularity' => 110,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejor control financiero, consciencia de gastos, ahorro efectivo',
                'steps' => [
                    'CONFIGURACIÓN INICIAL: Elegir app de gastos (Mint, YNAB, Excel simple) o libreta dedicada. Crear categorías básicas: casa, comida, transporte, entretenimiento.',
                    'RITUAL DE RECOLECCIÓN: Al final del día (antes de dormir), recopilar todos recibos, revisar extractos bancarios, recordar gastos en efectivo.',
                    'REGISTRO INMEDIATO: Anotar cada gasto con: monto, categoría, si fue necesidad vs deseo, cómo te sentiste al gastarlo (satisfecho/arrepentido).',
                    'ANÁLISIS SEMANAL: Cada domingo, revisar patrones de la semana. Identificar categoría donde más gastaste, días de mayor gasto, triggers emocionales.',
                    'PRESUPUESTO POR CATEGORÍAS: Establecer límites semanales por categoría. Usar método 50/30/20: 50% necesidades, 30% deseos, 20% ahorros.',
                    'ALERTAS Y AJUSTES: Configurar notificaciones cuando te acerques al límite de una categoría. Ajustar comportamiento antes de exceder presupuesto.',
                    'RECOMPENSAS POR CUMPLIMIENTO: Si cumples presupuesto semanal, darte pequeña recompensa planificada. Refuerza el comportamiento positivo.'
                ]
            ],
            [
                'name' => 'Ahorrar dinero diariamente',
                'description' => 'Separar una cantidad fija de dinero cada día para ahorros',
                'icon' => '🏦',
                'categoria' => 'finanzas',
                'popularity' => 140,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Seguridad financiera, formación de disciplina, fondos de emergencia',
                'steps' => [
                    'CÁLCULO DE CAPACIDAD REALISTA: Analizar ingresos mensuales, restar gastos fijos, determinar 5-10% disponible para ahorro diario.',
                    'AUTOMATIZACIÓN INMEDIATA: Configurar transferencia automática apenas llegue el sueldo. "Págate a ti mismo primero" antes que cualquier gasto.',
                    'MÉTODO DE REDONDEO: Cada compra, redondear al peso/euro superior y diferencia va a ahorros. Compra $4.30, redondear a $5, ahorrar $0.70.',
                    'CUENTA SEPARADA INTOCABLE: Abrir cuenta de ahorros en banco diferente, sin tarjeta de débito asociada. Crear fricción para acceder.',
                    'TRACKING VISUAL MOTIVADOR: Usar app o gráfico físico para ver crecimiento diario del ahorro. Celebrar cada milestone ($100, $500, $1000).',
                    'DESAFÍOS DE AHORRO: Implementar retos como "52 semanas" (semana 1 ahorrar $1, semana 2 $2, etc.) o "challenge del peso diario".',
                    'PROPÓSITO ESPECÍFICO: Asignar objetivo concreto al ahorro (emergencias, vacaciones, curso). Tener meta específica aumenta motivación.'
                ]
            ],
            [
                'name' => 'Leer sobre finanzas',
                'description' => 'Dedicar tiempo diario a aprender sobre finanzas personales',
                'icon' => '📊',
                'categoria' => 'finanzas',
                'popularity' => 90,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Mejores decisiones financieras, aumento de ingresos, libertad financiera',
                'steps' => [
                    'SELECCIÓN DE FUENTES CONFIABLES: Identificar 3-5 fuentes educativas de calidad: libros, podcasts, blogs especializados, canales YouTube financieros.',
                    'TIEMPO SAGRADO DE APRENDIZAJE: Establecer 20 min diarios fijos (desayuno, transporte, antes de dormir). Convertir en ritual no negociable.',
                    'MÉTODO DE ANOTACIONES ACTIVAS: Tener libreta específica para conceptos financieros. Escribir 3 ideas clave por sesión de lectura/escucha.',
                    'APLICACIÓN INMEDIATA: Por cada concepto aprendido, implementar una acción concreta en 48 horas (revisar cuenta, cambiar inversión, ajustar presupuesto).',
                    'DIVERSIFICACIÓN TEMÁTICA: Rotar semanalmente entre temas: ahorro, inversión, seguros, impuestos, emprendimiento. Evitar saturación en un solo tema.',
                    'DISCUSIÓN Y ENSEÑANZA: Compartir 1 concepto aprendido por semana con amigo/familia. Enseñar consolida el aprendizaje propio.',
                    'TRACKING DE PROGRESO: Registrar libros leídos, conceptos aplicados, mejoras financieras logradas. Medir ROI del tiempo invertido en educación.'
                ]
            ],
            [
                'name' => 'Revisar inversiones',
                'description' => 'Monitorear y analizar el rendimiento de las inversiones semanalmente',
                'icon' => '📈',
                'categoria' => 'finanzas',
                'popularity' => 85,
                'frequency_suggestions' => ['semanal', '3 veces por semana'],
                'benefits' => 'Mejor rendimiento de inversiones, decisiones informadas, crecimiento patrimonial',
                'steps' => [
                    'CONFIGURACIÓN DE PORTFOLIO: Definir distribución de activos según perfil de riesgo. 70% acciones, 20% bonos, 10% efectivo (ejemplo conservador).',
                    'HERRAMIENTAS DE MONITOREO: Usar apps como Yahoo Finance, Google Finance o broker online para tracking en tiempo real.',
                    'REVISIÓN SEMANAL ESTRUCTURADA: Cada viernes, dedicar 30 min a revisar rendimiento, noticias relevantes, cambios en el mercado.',
                    'CRITERIOS DE REBALANCEO: Establecer reglas claras: si una categoría desvía +/-5% del objetivo, rebalancear hacia distribución original.',
                    'EDUCACIÓN CONTINUA: Seguir 3-4 analistas financieros confiables, leer informes trimestrales de empresas en tu portfolio.',
                    'ESTRATEGIA DE LARGO PLAZO: Mantener perspectiva de 5-10 años, no tomar decisiones impulsivas por volatilidad diaria.',
                    'DOCUMENTACIÓN DE DECISIONES: Registrar por qué compraste/vendiste cada activo, evaluar mensualmente la calidad de tus decisiones.'
                ]
            ],
            [
                'name' => 'Planificar presupuesto semanal',
                'description' => 'Crear y ajustar presupuesto para la semana siguiente',
                'icon' => '📋',
                'categoria' => 'finanzas',
                'popularity' => 130,
                'frequency_suggestions' => ['semanal'],
                'benefits' => 'Control de gastos, evita sobreendeudamiento, alcanza metas financieras',
                'steps' => [
                    'ANÁLISIS DE INGRESOS SEMANALES: Calcular dinero disponible para la semana (salario/4 - gastos fijos proporcionales).',
                    'CATEGORIZACIÓN 50/30/20: Asignar 50% necesidades básicas, 30% gastos personales/entretenimiento, 20% ahorros/emergencias.',
                    'PLANIFICACIÓN DE GASTOS FIJOS: Listar gastos inevitables: transporte, comidas, servicios. Asignar monto específico a cada categoría.',
                    'FONDO DE CONTINGENCIA SEMANAL: Reservar 10-15% del presupuesto para gastos imprevistos que siempre aparecen.',
                    'HERRAMIENTAS DE SEGUIMIENTO: Usar envelope method (sobres físicos/digitales) o apps como YNAB para tracking en tiempo real.',
                    'REVISIÓN DE CUMPLIMIENTO: Cada domingo, comparar gastos reales vs presupuesto planificado. Identificar desvíos y causas.',
                    'AJUSTES PARA PRÓXIMA SEMANA: Basado en resultados, ajustar presupuesto siguiente. Si gastaste menos en comida, quizás puedes aumentar entretenimiento.'
                ]
            ],

            // RELACIONES (7 hábitos)
            [
                'name' => 'Llamar a familia',
                'description' => 'Mantener contacto regular con familiares importantes',
                'icon' => '📞',
                'categoria' => 'relaciones',
                'popularity' => 125,
                'frequency_suggestions' => ['diario', '3 veces por semana'],
                'benefits' => 'Fortalece vínculos familiares, apoyo emocional, conexión',
                'steps' => [
                    'MAPEO DE FAMILIA PRIORITARIA: Hacer lista de 5-7 familiares más importantes. Asignar frecuencia específica: padres (2x semana), hermanos (1x semana).',
                    'CALENDARIO DE LLAMADAS: Programar días/horarios específicos para cada familiar. Tratar como citas importantes, configurar recordatorios.',
                    'PREPARACIÓN DE CONVERSACIÓN: Antes de llamar, pensar 2-3 preguntas específicas sobre su vida, trabajo, intereses. Evitar conversaciones superficiales.',
                    'ESCUCHA ACTIVA GENUINA: Durante llamada, eliminar distracciones (TV, redes sociales). Hacer preguntas de seguimiento, mostrar interés real.',
                    'RECORDATORIO DE DETALLES: Después de cada llamada, anotar 2-3 cosas importantes que mencionaron para preguntar en próxima conversación.',
                    'ALTERNATIVAS CREATIVAS: Rotar entre llamadas de voz, videollamadas, mensajes de audio. Enviar fotos ocasionales de actividades diarias.',
                    'APROVECHAMIENTO DE MOMENTOS: Llamar durante actividades rutinarias como caminar, hacer ejercicio, trayectos en transporte público.'
                ]
            ],
            [
                'name' => 'Enviar mensaje positivo',
                'description' => 'Enviar mensaje de aliento o cariño a alguien importante',
                'icon' => '💌',
                'categoria' => 'relaciones',
                'popularity' => 100,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Fortalece relaciones, genera alegría mutua, expande red de apoyo',
                'steps' => [
                    'LISTA ROTATIVA DE CONTACTOS: Crear lista de 20-30 personas importantes (familia, amigos, colegas). Rotar para que nadie quede olvidado.',
                    'MENSAJES AUTÉNTICOS Y ESPECÍFICOS: Evitar "¿Cómo estás?" genérico. Mencionar algo específico: logro reciente, recuerdo compartido, apreciación genuina.',
                    'VARIEDAD EN FORMATO: Alternar entre texto, mensaje de voz, foto con comentario positivo, GIF divertido, artículo interesante para ellos.',
                    'TIMING ESTRATÉGICO: Enviar en momentos cuando más apoyo necesitan: lunes (motivación semanal), viernes (celebración), días importantes.',
                    'SIN EXPECTATIVA DE RESPUESTA: Enviar desde generosidad genuina, no esperando respuesta inmediata. Mencionar "no necesitas responder, solo quería saludarte".',
                    'TRACKING DE IMPACTO: Observar cómo mejoran tus relaciones. Notar quiénes responden positivamente y profundizar esas conexiones.',
                    'APROVECHAMIENTO DE FECHAS: Recordar cumpleaños, aniversarios, logros. Usar calendario para programar mensajes en fechas significativas.'
                ]
            ],

            // ESTUDIO/APRENDIZAJE (8 hábitos)
            [
                'name' => 'Leer 30 minutos',
                'description' => 'Dedicar tiempo diario a la lectura para expandir conocimientos',
                'icon' => '📚',
                'categoria' => 'aprendizaje',
                'popularity' => 155,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Expande vocabulario, mejora concentración, adquiere conocimiento',
                'steps' => [
                    'SELECCIÓN ESTRATÉGICA DE MOMENTO: Identificar momento del día cuando tengas más energía mental. Muchos prefieren antes de dormir o primera hora.',
                    'ESPACIO DEDICADO SOLO A LECTURA: Crear rincón específico con buena luz, cómodo, sin distracciones. Asociar lugar con actividad para formar hábito.',
                    'REGLA DE PÁGINAS MÍNIMAS: Establecer meta mínima (5-10 páginas) para días difíciles. Meta máxima flexible según disponibilidad y engagement.',
                    'VARIEDAD DE FORMATOS: Combinar libro físico, e-book, audiolibro según contexto. Audiolibro mientras ejercitas, físico antes de dormir.',
                    'SISTEMA DE NOTAS Y REFLEXIÓN: Tener libreta o app para capturar ideas importantes. Hacer resumen de capítulo o reflexión personal post-lectura.',
                    'LISTA DE LIBROS PENDIENTES: Mantener lista de próximos libros para evitar tiempo perdido eligiendo. Incluir diferentes géneros para variedad.',
                    'TRACKING DE PROGRESO: Registrar libros completados, páginas leídas, conceptos aprendidos. Visualizar crecimiento intelectual a largo plazo.'
                ]
            ],
            [
                'name' => 'Aprender idioma',
                'description' => 'Practicar un nuevo idioma durante 20 minutos diarios',
                'icon' => '🗣️',
                'categoria' => 'aprendizaje',
                'popularity' => 120,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Mejora capacidades cognitivas, oportunidades profesionales',
                'steps' => [
                    'ELECCIÓN DE MÉTODO ESTRUCTURADO: Combinar app principal (Duolingo, Babbel) + podcast + contenido nativo. Diversificar fuentes de aprendizaje.',
                    'RUTINA DE INMERSIÓN DIARIA: 20 min divididos: 10 min app/lecciones, 5 min escucha (música/podcast), 5 min práctica activa (hablar solo).',
                    'VOCABULARIO CONTEXTUAL: Aprender 5 palabras nuevas diarias relacionadas con situaciones reales. Usar en frases completas, no solo memorizar.',
                    'PRÁCTICA DE CONVERSACIÓN: Buscar intercambio de idiomas online (HelloTalk, Tandem) 2-3 veces por semana. Perder miedo a cometer errores.',
                    'CONSUMO DE CONTENIDO NATIVO: Gradualmente introducir música, videos YouTube, películas con subtítulos en idioma objetivo.',
                    'DIARIO DE PROGRESO: Escribir 2-3 frases diarias en idioma objetivo sobre actividades del día. Documenta evolución de fluidez.',
                    'METAS ESPECÍFICAS SEMANALES: Establecer objetivos concretos: aprender números, presentarse, describir familia. Celebrar cada logro pequeño.'
                ]
            ],
            [
                'name' => 'Escribir en diario',
                'description' => 'Reflexionar por escrito sobre el día, pensamientos y experiencias',
                'icon' => '📖',
                'categoria' => 'aprendizaje',
                'popularity' => 110,
                'frequency_suggestions' => ['diario'],
                'benefits' => 'Mejora autoconocimiento, habilidades de escritura, procesamiento emocional',
                'steps' => [
                    'MOMENTO SAGRADO PERSONAL: Elegir momento íntimo del día (antes de dormir recomendado). Crear ritual: té, música suave, espacio cómodo.',
                    'ESTRUCTURA FLEXIBLE PERO CONSISTENTE: Usar formato: 3 momentos del día + 1 emoción + 1 aprendizaje + 1 gratitud. Adaptar según necesidades.',
                    'ESCRITURA SIN CENSURA: Escribir pensamientos tal como vienen, sin juzgar gramática o coherencia. Priorizar autenticidad sobre perfección.',
                    'EXPLORACIÓN DE EMOCIONES: Identificar y nombrar emociones específicas. Explorar por qué surgieron, qué las disparó, cómo gestionarlas mejor.',
                    'REFLEXIÓN SOBRE PATRONES: Semanalmente, releer entradas buscando patrones de comportamiento, emociones recurrentes, áreas de crecimiento.',
                    'CONEXIÓN CON OBJETIVOS: Relacionar experiencias diarias con metas a largo plazo. Evaluar si acciones diarias alinean con valores personales.',
                    'EVOLUCIÓN DE PREGUNTAS: Cambiar preguntas guía mensualmente: "¿Qué me hizo feliz?", "¿Cómo puedo mejorar?", "¿Qué descubrí sobre mí?"'
                ]
            ],
            [
                'name' => 'Curso online diario',
                'description' => 'Dedicar tiempo a aprender algo nuevo através de cursos online',
                'icon' => '💻',
                'categoria' => 'aprendizaje',
                'popularity' => 95,
                'frequency_suggestions' => ['diario', '5 veces por semana'],
                'benefits' => 'Desarrollo profesional, nuevas habilidades, crecimiento personal',
                'steps' => [
                    'SELECCIÓN ESTRATÉGICA DE CURSO: Elegir curso alineado con objetivos profesionales o intereses personales. Leer reseñas, verificar instructor.',
                    'COMPROMISO TEMPORAL REALISTA: Dedicar 25-30 min diarios máximo. Mejor consistencia diaria que sesiones largas esporádicas.',
                    'APRENDIZAJE ACTIVO: Tomar notas a mano durante lecciones. Pausar video para reflexionar, hacer preguntas, conectar con conocimiento previo.',
                    'PRÁCTICA INMEDIATA: Por cada concepto aprendido, buscar forma de aplicarlo en 24-48 horas. Crear proyecto pequeño para practicar habilidades.',
                    'COMUNIDAD DE APRENDIZAJE: Participar en foros del curso, unirse a grupos de estudio online, compartir progreso en redes sociales.',
                    'PORTFOLIO DE PROYECTOS: Documentar proyectos y ejercicios realizados. Crear carpeta digital con trabajos para mostrar progreso.',
                    'CERTIFICACIÓN Y SEGUIMIENTO: Completar cursos hasta obtener certificado. Añadir a LinkedIn, CV. Planificar siguiente curso antes de terminar actual.'
                ]
            ]
        ];

        // Limpiar sugerencias existentes
        HabitSuggestion::truncate();

        // Insertar nuevas sugerencias
        foreach ($suggestions as $suggestion) {
            HabitSuggestion::create($suggestion);
        }

        $this->command->info('Se han creado ' . count($suggestions) . ' sugerencias de hábitos extendidas');
    }
}
