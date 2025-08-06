<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Hábitos - Motiveo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1f2937',
                        'secondary': '#374151',
                        'accent': '#3b82f6',
                        'success': '#10b981',
                        'danger': '#ef4444',
                        'warning': '#f59e0b',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="h-full bg-gray-50 font-sans" x-data="habitQuizApp()">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-sm font-bold text-white">M</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">Quiz de Hábitos</h1>
                </div>
                <a href="/" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Quiz Loading State -->
        <div x-show="!quizStarted && !quizCompleted" class="text-center">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-white text-2xl font-bold">?</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Quiz de Refuerzo</h2>
                <p class="text-gray-600 mb-2" x-text="`Fortalece tu hábito: ${currentHabit.nombre || 'Cargando...'}`"></p>
                <p class="text-sm text-gray-500 mb-8">Responde 5 preguntas rápidas para reforzar tu compromiso y ganar +5 XP</p>
                <button @click="startQuiz()" 
                        :disabled="!currentHabit.nombre"
                        :class="currentHabit.nombre ? 'bg-accent hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full py-4 text-white rounded-lg font-semibold text-lg transition-colors">
                    Comenzar Quiz
                </button>
            </div>
        </div>

        <!-- Quiz Progress Bar -->
        <div x-show="quizStarted && !quizCompleted" class="mb-8">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span x-text="`Pregunta ${currentQuestionIndex + 1} de ${questions.length}`"></span>
                <span x-text="`${Math.round(((currentQuestionIndex + 1) / questions.length) * 100)}%`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-accent h-3 rounded-full transition-all duration-500"
                     :style="`width: ${((currentQuestionIndex + 1) / questions.length) * 100}%`"></div>
            </div>
        </div>

        <!-- Quiz Question -->
        <div x-show="quizStarted && !quizCompleted && !showingFeedback" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4" x-text="currentQuestion.question"></h3>
                <div x-show="currentQuestion.image" class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl" x-text="currentQuestion.image"></span>
                </div>
            </div>

            <div class="space-y-3">
                <template x-for="(option, index) in currentQuestion.options" :key="index">
                    <button @click="selectAnswer(option)"
                            class="w-full p-4 text-left border-2 border-gray-200 rounded-lg hover:border-accent hover:bg-blue-50 transition-all duration-200">
                        <span class="text-gray-900 font-medium" x-text="option.text"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Feedback -->
        <div x-show="showingFeedback" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"
                 :class="lastAnswerCorrect ? 'bg-success' : 'bg-danger'">
                <span class="text-white text-2xl font-bold" x-text="lastAnswerCorrect ? '✓' : '✗'"></span>
            </div>
            <h3 class="text-xl font-semibold mb-4"
                :class="lastAnswerCorrect ? 'text-success' : 'text-danger'"
                x-text="lastAnswerCorrect ? 'Buena elección!' : 'Puedes mejorar, inténtalo mañana'"></h3>
            <p class="text-gray-600 mb-8" x-text="feedbackMessage"></p>
            <button @click="nextQuestion()"
                    class="bg-accent text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                <span x-text="currentQuestionIndex < questions.length - 1 ? 'Siguiente' : 'Ver Resultados'"></span>
            </button>
        </div>

        <!-- Quiz Results -->
        <div x-show="quizCompleted" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <div class="w-20 h-20 bg-success rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-white text-3xl font-bold">★</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">¡Excelente trabajo, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600 mb-6">
                Estás más cerca de convertir este hábito en parte de tu vida.
            </p>
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <div class="text-3xl font-bold text-success mb-2" x-text="`${correctAnswers}/${questions.length}`">0/5</div>
                <div class="text-sm text-gray-600 mb-4">Respuestas correctas</div>
                <div class="text-lg font-semibold text-accent">+5 XP ganados</div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button @click="restartQuiz()"
                        class="flex-1 bg-gray-100 text-gray-900 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Repetir Quiz
                </button>
                <a href="/"
                   class="flex-1 bg-accent text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-600 transition-colors text-center">
                    Ir al Dashboard
                </a>
            </div>
        </div>

        <!-- Habit Info Card -->
        <div x-show="currentHabit.nombre" class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-gray-900" x-text="currentHabit.nombre"></h4>
                    <p class="text-sm text-gray-600 capitalize" x-text="currentHabit.categoria"></p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900" x-text="`Día ${currentHabit.current_day || 1} de ${currentHabit.duration_days || 30}`"></div>
                    <div class="text-xs text-gray-500">Hábito activo</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function habitQuizApp() {
            return {
                quizStarted: false,
                quizCompleted: false,
                showingFeedback: false,
                currentQuestionIndex: 0,
                currentQuestion: {},
                questions: [],
                correctAnswers: 0,
                lastAnswerCorrect: false,
                feedbackMessage: '',
                currentHabit: {},

                // Función para lanzar confetti al subir de nivel
                launchConfetti() {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 },
                        colors: ['#3b82f6', '#10b981', '#f59e0b']
                    });
                    
                    setTimeout(() => {
                        confetti({
                            particleCount: 50,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0 },
                            colors: ['#3b82f6', '#10b981']
                        });
                    }, 200);
                    
                    setTimeout(() => {
                        confetti({
                            particleCount: 50,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1 },
                            colors: ['#3b82f6', '#10b981']
                        });
                    }, 400);
                },

                init() {
                    this.loadCurrentHabit();
                },

                async loadCurrentHabit() {
                    try {
                        // Obtener hábitos del usuario
                        const response = await fetch('/api/user-habits');
                        const data = await response.json();
                        
                        // Tomar el primer hábito activo
                        if (data.active_habits && data.active_habits.length > 0) {
                            this.currentHabit = data.active_habits[0];
                            this.generateQuestions();
                        } else {
                            this.currentHabit = {
                                nombre: 'Hábito general',
                                categoria: 'bienestar'
                            };
                            this.generateQuestions();
                        }
                    } catch (error) {
                        console.error('Error loading habit:', error);
                        this.currentHabit = {
                            nombre: 'Hábito general',
                            categoria: 'bienestar'
                        };
                        this.generateQuestions();
                    }
                },

                generateQuestions() {
                    this.questions = this.obtenerPreguntasPorHabito(this.currentHabit);
                },

                obtenerPreguntasPorHabito(habit) {
                    const categoria = habit.categoria || 'bienestar';
                    const nombre = habit.nombre || 'tu hábito';

                    const preguntasPorCategoria = {
                        salud: [
                            {
                                question: `¿Cuál es tu principal motivación para mantener ${nombre}?`,
                                image: '💪',
                                options: [
                                    { text: 'Sentirme más energético y saludable', correct: true, feedback: 'La energía es una excelente motivación a largo plazo.' },
                                    { text: 'Cumplir con una meta impuesta', correct: false, feedback: 'Las motivaciones internas son más duraderas que las externas.' },
                                    { text: 'Porque otros lo hacen', correct: false, feedback: 'Tu motivación debe ser personal y significativa para ti.' },
                                    { text: 'No estoy seguro', correct: false, feedback: 'Reflexiona sobre qué beneficios esperas obtener.' }
                                ]
                            },
                            {
                                question: '¿Qué harás si un día no puedes completar tu hábito?',
                                image: '🤔',
                                options: [
                                    { text: 'Me enfocaré en retomarlo al día siguiente', correct: true, feedback: 'Excelente actitud. La consistencia es más importante que la perfección.' },
                                    { text: 'Me sentiré mal y posiblemente abandone', correct: false, feedback: 'Los tropiezos son normales. Lo importante es continuar.' },
                                    { text: 'Esperaré a la próxima semana para empezar', correct: false, feedback: 'No pospongas. Cada día es una nueva oportunidad.' },
                                    { text: 'No me importa mucho', correct: false, feedback: 'Tu hábito debe ser importante para ti.' }
                                ]
                            },
                            {
                                question: '¿Cuándo es el mejor momento para practicar tu hábito?',
                                image: '⏰',
                                options: [
                                    { text: 'A la misma hora todos los días', correct: true, feedback: 'La consistencia en horarios fortalece el hábito.' },
                                    { text: 'Cuando me acuerde', correct: false, feedback: 'La planificación es clave para el éxito.' },
                                    { text: 'Solo los fines de semana', correct: false, feedback: 'La frecuencia diaria es más efectiva.' },
                                    { text: 'Cuando tenga ganas', correct: false, feedback: 'Los hábitos se construyen con disciplina, no solo motivación.' }
                                ]
                            },
                            {
                                question: '¿Cómo celebrarás tus pequeños logros?',
                                image: '🎉',
                                options: [
                                    { text: 'Reconociendo mi progreso y sintiéndome orgulloso', correct: true, feedback: 'El auto-reconocimiento refuerza comportamientos positivos.' },
                                    { text: 'No necesito celebrar cosas pequeñas', correct: false, feedback: 'Los pequeños logros son la base de grandes cambios.' },
                                    { text: 'Esperaré a completar todo el desafío', correct: false, feedback: 'Celebrar el progreso mantiene la motivación alta.' },
                                    { text: 'Con comida chatarra como premio', correct: false, feedback: 'Elige recompensas que apoyen tus objetivos de salud.' }
                                ]
                            },
                            {
                                question: `¿Qué beneficio esperas más de ${nombre}?`,
                                image: '✨',
                                options: [
                                    { text: 'Mayor bienestar físico y mental', correct: true, feedback: 'Los hábitos de salud impactan positivamente en todos los aspectos de la vida.' },
                                    { text: 'Impresionar a otros', correct: false, feedback: 'Los cambios duraderos vienen de motivaciones internas.' },
                                    { text: 'Cumplir con expectativas sociales', correct: false, feedback: 'Tu bienestar debe ser la prioridad principal.' },
                                    { text: 'No tengo expectativas claras', correct: false, feedback: 'Tener objetivos claros aumenta las probabilidades de éxito.' }
                                ]
                            }
                        ],
                        productividad: [
                            {
                                question: `¿Cuál es tu objetivo principal con ${nombre}?`,
                                image: '🎯',
                                options: [
                                    { text: 'Ser más eficiente y organizado en mi día', correct: true, feedback: 'La eficiencia es la base de una vida productiva.' },
                                    { text: 'Aparentar que soy productivo', correct: false, feedback: 'La productividad real se enfoca en resultados, no apariencias.' },
                                    { text: 'Trabajar más horas', correct: false, feedback: 'La calidad del trabajo es más importante que la cantidad.' },
                                    { text: 'Seguir tendencias de productividad', correct: false, feedback: 'Encuentra métodos que realmente funcionen para ti.' }
                                ]
                            },
                            {
                                question: '¿Cómo manejarás las distracciones?',
                                image: '🔍',
                                options: [
                                    { text: 'Identificaré y eliminaré las principales fuentes de distracción', correct: true, feedback: 'Controlar el entorno es clave para mantener el foco.' },
                                    { text: 'Confiaré en mi fuerza de voluntad solamente', correct: false, feedback: 'La fuerza de voluntad es limitada. Mejor crea sistemas.' },
                                    { text: 'Las ignoraré y continuaré', correct: false, feedback: 'Es mejor prevenir las distracciones que luchar contra ellas.' },
                                    { text: 'No es necesario, no me distraigo', correct: false, feedback: 'Todos tenemos distracciones. Reconocerlas es el primer paso.' }
                                ]
                            },
                            {
                                question: '¿Qué harás cuando te sientas abrumado?',
                                image: '🧘',
                                options: [
                                    { text: 'Tomaré un descanso y reorganizaré mis prioridades', correct: true, feedback: 'Los descansos estratégicos mejoran la productividad a largo plazo.' },
                                    { text: 'Trabajaré más duro para compensar', correct: false, feedback: 'Trabajar más cuando estás abrumado puede ser contraproducente.' },
                                    { text: 'Abandonaré algunas tareas importantes', correct: false, feedback: 'Mejor evalúa y reorganiza en lugar de abandonar.' },
                                    { text: 'Esperaré a que pase la sensación', correct: false, feedback: 'La acción proactiva es más efectiva que esperar.' }
                                ]
                            },
                            {
                                question: '¿Cómo medirás tu progreso?',
                                image: '📊',
                                options: [
                                    { text: 'Con métricas específicas y revisiones regulares', correct: true, feedback: 'Medir el progreso te permite ajustar y mejorar continuamente.' },
                                    { text: 'Por cómo me siento al final del día', correct: false, feedback: 'Los sentimientos pueden engañar. Los datos objetivos son más confiables.' },
                                    { text: 'No necesito medir, lo sabré', correct: false, feedback: 'Lo que no se mide, no se puede mejorar efectivamente.' },
                                    { text: 'Comparándome con otros', correct: false, feedback: 'Tu progreso debe medirse contra tus propios objetivos.' }
                                ]
                            },
                            {
                                question: `¿Cuándo practicarás ${nombre} para ser más consistente?`,
                                image: '📅',
                                options: [
                                    { text: 'A la misma hora cada día, como parte de mi rutina', correct: true, feedback: 'Las rutinas automatizan los buenos hábitos.' },
                                    { text: 'Cuando tenga tiempo libre', correct: false, feedback: 'Lo importante debe tener tiempo asignado, no tiempo libre.' },
                                    { text: 'Solo cuando esté motivado', correct: false, feedback: 'La consistencia se basa en sistemas, no en motivación.' },
                                    { text: 'Los fines de semana principalmente', correct: false, feedback: 'La práctica diaria construye hábitos más sólidos.' }
                                ]
                            }
                        ],
                        bienestar: [
                            {
                                question: `¿Qué esperas lograr con ${nombre}?`,
                                image: '🌟',
                                options: [
                                    { text: 'Mayor paz mental y equilibrio emocional', correct: true, feedback: 'El bienestar emocional es fundamental para una vida plena.' },
                                    { text: 'Eliminar completamente el estrés', correct: false, feedback: 'Es más realista aprender a manejar el estrés que eliminarlo totalmente.' },
                                    { text: 'Ser siempre feliz', correct: false, feedback: 'Las emociones variadas son normales y saludables.' },
                                    { text: 'Impresionar a otros con mi calma', correct: false, feedback: 'El bienestar debe ser para ti, no para la aprobación externa.' }
                                ]
                            },
                            {
                                question: '¿Cómo vas a mantener este hábito en días difíciles?',
                                image: '💪',
                                options: [
                                    { text: 'Adaptaré la práctica pero la mantendré', correct: true, feedback: 'La flexibilidad ayuda a mantener la consistencia a largo plazo.' },
                                    { text: 'Lo saltaré y continuaré al día siguiente', correct: false, feedback: 'Incluso una versión reducida es mejor que saltarlo completamente.' },
                                    { text: 'Esperaré a sentirme mejor', correct: false, feedback: 'Los días difíciles son cuando más necesitas tus hábitos de bienestar.' },
                                    { text: 'No creo que tenga días difíciles', correct: false, feedback: 'Es sabio prepararse para los desafíos inevitables.' }
                                ]
                            },
                            {
                                question: '¿Qué te ayudará a recordar practicar tu hábito?',
                                image: '💡',
                                options: [
                                    { text: 'Recordatorios específicos y un entorno preparado', correct: true, feedback: 'Los recordatorios externos compensan nuestra memoria limitada.' },
                                    { text: 'Confío en que lo recordaré naturalmente', correct: false, feedback: 'Incluso las personas organizadas usan recordatorios.' },
                                    { text: 'Solo cuando sienta que lo necesito', correct: false, feedback: 'Los hábitos regulares son más efectivos que las prácticas esporádicas.' },
                                    { text: 'Esperaré a crear el hábito de recordar', correct: false, feedback: 'Usa herramientas externas mientras desarrollas el hábito interno.' }
                                ]
                            },
                            {
                                question: '¿Cómo sabrás que tu hábito está funcionando?',
                                image: '🎯',
                                options: [
                                    { text: 'Notaré cambios graduales en mi estado de ánimo y energía', correct: true, feedback: 'Los cambios reales suelen ser graduales pero sostenidos.' },
                                    { text: 'Me sentiré diferente inmediatamente', correct: false, feedback: 'Los hábitos de bienestar requieren tiempo para mostrar resultados.' },
                                    { text: 'Otros me lo dirán', correct: false, feedback: 'Tú eres el mejor juez de tu propio bienestar.' },
                                    { text: 'Solo si es perfecto desde el inicio', correct: false, feedback: 'El progreso imperfecto sigue siendo progreso valioso.' }
                                ]
                            },
                            {
                                question: `¿Cuál será tu enfoque principal al practicar ${nombre}?`,
                                image: '🧘',
                                options: [
                                    { text: 'Estar presente y enfocado en el momento', correct: true, feedback: 'La atención plena maximiza los beneficios de cualquier práctica.' },
                                    { text: 'Hacerlo rápido para terminar pronto', correct: false, feedback: 'La calidad de la práctica es más importante que la velocidad.' },
                                    { text: 'Pensar en otras cosas mientras lo hago', correct: false, feedback: 'La distracción mental reduce la efectividad del hábito.' },
                                    { text: 'Solo completar la tarea', correct: false, feedback: 'La intención y la presencia mental potencian los resultados.' }
                                ]
                            }
                        ],
                        aprendizaje: [
                            {
                                question: `¿Cuál es tu motivación principal para ${nombre}?`,
                                image: '📚',
                                options: [
                                    { text: 'Crecer personalmente y expandir mis conocimientos', correct: true, feedback: 'El crecimiento personal es una motivación poderosa y duradera.' },
                                    { text: 'Parecer más inteligente ante otros', correct: false, feedback: 'Aprende para ti mismo, no para impresionar a otros.' },
                                    { text: 'Porque está de moda', correct: false, feedback: 'Las motivaciones internas son más sostenibles que las tendencias.' },
                                    { text: 'No tengo una razón específica', correct: false, feedback: 'Tener un propósito claro aumenta la motivación para continuar.' }
                                ]
                            },
                            {
                                question: '¿Cómo aplicarás lo que aprendas?',
                                image: '⚡',
                                options: [
                                    { text: 'Buscaré oportunidades para practicar y usar el conocimiento', correct: true, feedback: 'La aplicación práctica solidifica el aprendizaje.' },
                                    { text: 'Lo guardaré para usarlo algún día', correct: false, feedback: 'El conocimiento sin aplicación se olvida fácilmente.' },
                                    { text: 'Solo lo aprenderé por cultura general', correct: false, feedback: 'El aprendizaje activo es más efectivo que el pasivo.' },
                                    { text: 'No necesito aplicarlo, solo saberlo', correct: false, feedback: 'La aplicación transforma información en sabiduría.' }
                                ]
                            },
                            {
                                question: '¿Qué harás cuando el material sea difícil?',
                                image: '🧩',
                                options: [
                                    { text: 'Lo dividiré en partes más pequeñas y buscaré ayuda', correct: true, feedback: 'Dividir los desafíos los hace más manejables.' },
                                    { text: 'Lo saltaré y seguiré adelante', correct: false, feedback: 'Los conceptos difíciles suelen ser los más importantes.' },
                                    { text: 'Me frustraré y probablemente pare', correct: false, feedback: 'La frustración es normal. Desarrolla estrategias para manejarla.' },
                                    { text: 'Esperaré a que se vuelva más fácil', correct: false, feedback: 'El aprendizaje requiere enfrentar la dificultad, no evitarla.' }
                                ]
                            },
                            {
                                question: '¿Cómo mantendrás la consistencia en tu aprendizaje?',
                                image: '📅',
                                options: [
                                    { text: 'Dedicaré tiempo específico cada día, aunque sea poco', correct: true, feedback: 'Poco y constante es más efectivo que mucho e inconsistente.' },
                                    { text: 'Estudiaré intensivamente los fines de semana', correct: false, feedback: 'La práctica diaria construye mejores hábitos de aprendizaje.' },
                                    { text: 'Solo cuando tenga mucho tiempo libre', correct: false, feedback: 'El aprendizaje debe ser una prioridad, no una actividad de tiempo libre.' },
                                    { text: 'Dependiendo de mi estado de ánimo', correct: false, feedback: 'Los sistemas consistentes superan a la motivación variable.' }
                                ]
                            },
                            {
                                question: `¿Cómo medirás tu progreso en ${nombre}?`,
                                image: '📈',
                                options: [
                                    { text: 'Evaluando regularmente lo que puedo hacer ahora vs antes', correct: true, feedback: 'Comparar tu progreso contigo mismo es la mejor medida.' },
                                    { text: 'Comparándome con otros estudiantes', correct: false, feedback: 'Tu ritmo de aprendizaje es único y personal.' },
                                    { text: 'Solo por la cantidad de tiempo invertido', correct: false, feedback: 'La calidad del aprendizaje es más importante que el tiempo.' },
                                    { text: 'No necesito medir el progreso', correct: false, feedback: 'Medir el progreso te ayuda a mantenerte motivado y ajustar métodos.' }
                                ]
                            }
                        ]
                    };

                    return preguntasPorCategoria[categoria] || preguntasPorCategoria.bienestar;
                },

                startQuiz() {
                    this.quizStarted = true;
                    this.currentQuestionIndex = 0;
                    this.correctAnswers = 0;
                    this.currentQuestion = this.questions[0];
                },

                selectAnswer(option) {
                    this.lastAnswerCorrect = option.correct;
                    this.feedbackMessage = option.feedback;
                    this.showingFeedback = true;
                    
                    if (option.correct) {
                        this.correctAnswers++;
                    }
                },

                nextQuestion() {
                    this.showingFeedback = false;
                    
                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
                        this.currentQuestion = this.questions[this.currentQuestionIndex];
                    } else {
                        this.completeQuiz();
                    }
                },

                async completeQuiz() {
                    this.quizCompleted = true;
                    
                    // Enviar XP al servidor
                    try {
                        const response = await fetch('/quiz/complete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                habit_id: this.currentHabit.id,
                                score: this.correctAnswers,
                                total: this.questions.length
                            })
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            console.log('Quiz completed successfully, +5 XP awarded');
                            
                            // Verificar si subió de nivel y mostrar confetti
                            if (data.leveled_up) {
                                setTimeout(() => {
                                    this.launchConfetti();
                                }, 1000);
                            }
                        }
                    } catch (error) {
                        console.error('Error completing quiz:', error);
                    }
                },

                restartQuiz() {
                    this.quizStarted = false;
                    this.quizCompleted = false;
                    this.showingFeedback = false;
                    this.currentQuestionIndex = 0;
                    this.correctAnswers = 0;
                    this.generateQuestions(); // Regenerar preguntas para variedad
                }
            }
        }
    </script>
</body>
</html>
