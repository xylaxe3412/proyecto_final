<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Motiveo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-purple-600 font-display overflow-x-hidden" x-data="habitApp()" 
      x-init="$nextTick(() => { document.body.classList.add('loaded') })"
      style="opacity: 0; transform: translateY(20px); transition: all 0.6s ease;"
      x-transition:enter="transition ease-out duration-1000"
      x-transition:enter-start="opacity-0 transform translate-y-8"
      x-transition:enter-end="opacity-100 transform translate-y-0">
    <!-- Header -->
    @include('components.header')

    <!-- Notifications -->
    @include('components.notifications')

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
    </script>
</body>
</html>
