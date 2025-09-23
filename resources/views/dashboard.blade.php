<!DOCTYPE html>
<html lang="es" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') } }); if (darkMode) document.documentElement.classList.add('dark')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Motiveo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'motiveo-primary': '#6366f1',
                        'motiveo-secondary': '#8b5cf6',
                        'motiveo-accent': '#06b6d4',
                        'motiveo-success': '#10b981',
                        'motiveo-warning': '#f59e0b',
                        'motiveo-pink': '#ec4899',
                        'motiveo-dark': '#1e1b4b'
                    },
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard-animations.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canvas Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <!-- Background Music Manager -->
    <script src="{{ asset('js/background-music.js') }}"></script>
    <!-- Sound Effects Manager -->
    <script src="{{ asset('js/sound-effects.js') }}"></script>
    <!-- Lottie Animation Library -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:bg-gradient-to-br dark:from-slate-900 dark:to-purple-600 font-display overflow-x-hidden text-gray-900 dark:text-white"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', ...habitApp() }"
      x-init="$nextTick(() => { document.body.classList.add('loaded') }); if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}"

      x-effect="localStorage.setItem('darkMode', darkMode); if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}">
    <!-- Botón de tema flotante -->
    <div class="fixed bottom-6 right-6 z-[100] transform-gpu">
        <button @click="darkMode = !darkMode"
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white/90 dark:bg-gray-800/90 shadow-lg backdrop-blur-sm hover:scale-110 transition-all duration-200">
            <span x-show="!darkMode" class="text-gray-800"><i class="fas fa-moon text-lg"></i></span>
            <span x-show="darkMode" class="text-yellow-400"><i class="fas fa-sun text-lg"></i></span>
        </button>
    </div>
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900">
        <div class="bg-white dark:bg-slate-900 shadow-md dark:shadow-none border-b border-gray-200 dark:border-gray-800">
        @include('components.header')
    </div>
    </div>

    <!-- Notifications -->
    <div class="bg-white dark:bg-slate-900">
        <div class="bg-white dark:bg-transparent">
        @include('components.notifications')
    </div>
    </div>

    <!-- DEBUG: Botones de Prueba de Notificaciones de Racha (SOLO EN DESARROLLO) -->
    @if(config('app.debug'))
    <div class="fixed bottom-4 left-4 z-50 bg-gray-100 dark:bg-gray-900 p-3 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-75 hover:opacity-100 transition-opacity">
        <details class="text-gray-900 dark:text-white">
            <summary class="text-xs font-semibold cursor-pointer hover:text-blue-600 dark:hover:text-blue-300">🧪 Debug Tools</summary>
            <div class="mt-2 flex flex-col gap-1">
                <button onclick="if(confirm('¿Mostrar notificación de advertencia de racha?')) testStreakWarning()" 
                        class="px-2 py-1 bg-orange-100 dark:bg-orange-600 text-orange-900 dark:text-white rounded text-xs hover:bg-orange-200 dark:hover:bg-orange-700">
                    ⚠️ Test Warning
                </button>
                <button onclick="if(confirm('¿Mostrar notificación de racha iniciada?')) testStreakStarted()" 
                        class="px-2 py-1 bg-blue-100 dark:bg-blue-600 text-blue-900 dark:text-white rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-700">
                    🚀 Test Started
                </button>
                <button onclick="if(confirm('¿Mostrar notificación de racha salvada?')) testStreakSaved()" 
                        class="px-2 py-1 bg-green-100 dark:bg-green-600 text-green-900 dark:text-white rounded text-xs hover:bg-green-200 dark:hover:bg-green-700">
                    🔥 Test Saved
                </button>
                <button onclick="if(confirm('¿Mostrar notificación de nuevo récord?')) testStreakRecord()" 
                        class="px-2 py-1 bg-purple-100 dark:bg-purple-600 text-purple-900 dark:text-white rounded text-xs hover:bg-purple-200 dark:hover:bg-purple-700">
                    🏆 Test Record
                </button>
                <button onclick="if(confirm('¿Ejecutar todas las notificaciones de prueba?')) testAllNotifications()" 
                        class="px-2 py-1 bg-blue-100 dark:bg-blue-600 text-blue-900 dark:text-white rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-700">
                    🎯 Test All
                </button>
            </div>
        </details>
    </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12">
        <!-- Título y Filtros -->
        @include('components.dashboard-header')

        <!-- Grid de Hábitos -->
        <div class="habits-grid mb-24" x-show="userHabits.length > 0">
            <template x-for="(habit, index) in userHabits" :key="habit.id">
                @include('components.habit-card')
            </template>
        </div>

        <!-- Estado vacío -->
        @include('components.empty-state')

        <!-- Sección de Hábitos Sugeridos -->
        @include('components.suggestions-section')

        <!-- Panel de Estadísticas y Hábitos Completados -->
        @include('components.stats-panel')
    </div>
                             </div>
    <!-- Modal de Crear Nuevo Hábito -->
    @include('components.modals.create-habit')

    <!-- Modal de Editar Hábito -->
    @include('components.modals.edit-habit')

    <!-- Modal de hábito expandido -->
    @include('components.modals.expanded-habit')

    <!-- Modal de Explorador de Hábitos -->
    @include('components.modals.habit-explorer')

    <!-- Controles de Música de Fondo -->
    @include('components.music-controls')

    <!-- External JavaScript -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Initialize user stats from server -->
    <script>
        // Set initial user stats from server when page loads
        document.addEventListener('alpine:init', () => {
            const initialStats = {
                xp: {{ auth()->user()->xp ?? 0 }},
                level: {{ auth()->user()->level ?? 1 }},
                progress: {{ auth()->user()->getLevelProgress() ?? 0 }},
                next_level_xp: {{ auth()->user()->getXpForNextLevel() ?? 100 }}
            };
            
            // Override the init method to include server stats and session notifications
            const originalHabitApp = window.habitApp;
            window.habitApp = function() {
                const app = originalHabitApp();
                app.userStats = initialStats;
                
                // Override init to include session notifications
                const originalInit = app.init;
                app.init = function() {
                    originalInit.call(this);
                    
                    // Show XP notification if gained from login
                    @if(session('xp_gained'))
                    this.showNotification('{{ session('xp_gained.reason') }}');
                    @endif
                };
                
                return app;
            };
        });

        // 🧪 FUNCIONES DE PRUEBA PARA NOTIFICACIONES DE RACHA
        @if(config('app.debug'))
        
        // Funciones globales para usar desde la consola del navegador
        window.testStreakWarning = function() {
            console.log('🧪 [DEBUG] Iniciando prueba de advertencia de racha...');
            if (window.habitAppInstance && window.habitAppInstance.showStreakNotification) {
                console.log('✅ [DEBUG] Instancia encontrada, ejecutando notificación de prueba...');
                window.habitAppInstance.showStreakNotification('warning', 
                    '⚠️ [PRUEBA] ¡Tu racha de 15 días está en riesgo! Quedan menos de 2 horas', 
                    15
                );
                console.log('✅ [DEBUG] Notificación de advertencia mostrada (modo debug)');
            } else {
                console.error('❌ No se pudo encontrar la instancia de habitApp');
                console.log('💡 Instancia actual:', window.habitAppInstance);
                console.log('💡 Intenta ejecutar: debugStreakSystem()');
            }
        };

        window.testStreakStarted = function() {
            console.log('🧪 [DEBUG] Iniciando prueba de racha iniciada...');
            if (window.habitAppInstance && window.habitAppInstance.showStreakNotification) {
                console.log('✅ [DEBUG] Instancia encontrada, ejecutando notificación de prueba...');
                window.habitAppInstance.showStreakNotification('started', 
                    '🔥 [PRUEBA] ¡Racha iniciada! Comienza tu jornada de hábitos consecutivos', 
                    1
                );
                console.log('✅ [DEBUG] Notificación de racha iniciada mostrada (modo debug)');
            } else {
                console.error('❌ No se pudo encontrar la instancia de habitApp');
                console.log('💡 Instancia actual:', window.habitAppInstance);
                console.log('💡 Intenta ejecutar: debugStreakSystem()');
            }
        };

        window.testStreakSaved = function() {
            console.log('🧪 [DEBUG] Iniciando prueba de racha salvada...');
            if (window.habitAppInstance && window.habitAppInstance.showStreakNotification) {
                window.habitAppInstance.showStreakNotification('saved', 
                    '🔥 [PRUEBA] ¡Racha salvada! Llevas 16 días consecutivos', 
                    16
                );
                console.log('✅ [DEBUG] Notificación de racha salvada mostrada (modo debug)');
            }
        };

        window.testStreakRecord = function() {
            console.log('🧪 [DEBUG] Iniciando prueba de récord...');
            if (window.habitAppInstance && window.habitAppInstance.showStreakNotification) {
                window.habitAppInstance.showStreakNotification('record', 
                    '🏆 [PRUEBA] ¡Nuevo récord personal! 25 días consecutivos', 
                    25
                );
                console.log('✅ [DEBUG] Notificación de récord mostrada (modo debug)');
            }
        };

        window.testAllNotifications = function() {
            console.log('🧪 Ejecutando todas las notificaciones de prueba...');
            setTimeout(() => {
                testStreakWarning();
                console.log('1/4 - Advertencia enviada');
            }, 500);
            setTimeout(() => {
                testStreakStarted();
                console.log('2/4 - Racha iniciada enviada');
            }, 3000);
            setTimeout(() => {
                testStreakSaved();
                console.log('3/4 - Racha salvada enviada');
            }, 6000);
            setTimeout(() => {
                testStreakRecord();
                console.log('4/4 - Récord enviado');
            }, 9000);
        };

        // Funciones adicionales para debugging
        window.debugStreakSystem = function() {
            console.log('🔍 Estado del sistema de rachas:');
            console.log('habitAppInstance:', window.habitAppInstance);
            if (window.habitAppInstance) {
                console.log('streakData:', window.habitAppInstance.streakData);
                console.log('streakNotifications:', window.habitAppInstance.streakNotifications);
            }
        };

        window.forceLoadStreakData = function() {
            if (window.habitAppInstance && window.habitAppInstance.loadStreakData) {
                window.habitAppInstance.loadStreakData();
                console.log('🔄 Datos de racha recargados');
            }
        };

        // Log de ayuda para la consola
        console.log(`
🧪 HERRAMIENTAS DE DEBUG DISPONIBLES (Solo en desarrollo):
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
testStreakWarning()     - Mostrar advertencia de racha
testStreakStarted()     - Mostrar racha iniciada  
testStreakSaved()       - Mostrar racha salvada  
testStreakRecord()      - Mostrar nuevo récord
testAllNotifications()  - Probar todas las notificaciones
debugStreakSystem()     - Ver estado del sistema
forceLoadStreakData()   - Recargar datos de racha

💡 Estas funciones solo funcionan en modo debug
💡 Las notificaciones reales se activan completando hábitos
        `);
        @endif
    </script>
</body>
</html>
