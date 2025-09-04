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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canvas Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <!-- Lottie Animation Library -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <style>
        /* Fondo global con degradado morado */
        html, body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4c1d95 50%, #6b21a8 75%, #8b5cf6 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        
        /* Estilos para las tarjetas de hábitos */
        .habit-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        /* Estilos para animaciones Lottie */
        lottie-player {
            display: inline-block;
            min-width: 64px !important;
            min-height: 64px !important;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        lottie-player:hover {
            transform: scale(1.15);
        }
        }
        
        .habit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.1);
        }
        
        /* Hábitos pendientes - Resaltados */
        .habit-pending {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.3));
            border: 2px solid #f59e0b;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
            animation: pulseGlow 2s infinite;
        }
        
        /* Hábitos completados */
        .habit-completed {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(34, 197, 94, 0.3));
            border: 2px solid #10b981;
            opacity: 0.8;
        }
        
        /* Animación de resaltado para hábitos pendientes */
        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
                border-color: #f59e0b;
            }
            50% { 
                box-shadow: 0 0 30px rgba(245, 158, 11, 0.5);
                border-color: #fbbf24;
            }
        }
        
        /* Vista expandida */
        .habit-expanded {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90vw;
            max-width: 900px;
            height: 90vh;
            z-index: 1000;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: expandIn 0.3s ease-out;
        }
        
        /* Overlay para vista expandida */
        .habit-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Animaciones */
        @keyframes expandIn {
            from { 
                opacity: 0; 
                transform: translate(-50%, -50%) scale(0.9); 
            }
            to { 
                opacity: 1; 
                transform: translate(-50%, -50%) scale(1); 
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Efectos para pasos completados */
        .step-completed {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: #10b981;
        }
        
        .step-pending {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Botón de doble confirmación */
        .confirm-button {
            position: relative;
            overflow: hidden;
        }
        
        .confirm-button.confirming {
            background: linear-gradient(90deg, #f59e0b 0%, #f59e0b 50%, #10b981 50%, #10b981 100%);
            animation: confirmProgress 1.5s ease-in-out;
        }
        
        @keyframes confirmProgress {
            0% { background-position: -100% 0; }
            100% { background-position: 100% 0; }
        }
        
        /* Estilos específicos para dropdowns/selects */
        select {
            color-scheme: dark;
        }
        
        select option {
            background-color: #1f2937 !important;
            color: white !important;
            padding: 8px 12px;
        }
        
        select option:checked {
            background-color: #6366f1 !important;
            color: white !important;
        }
        
        select option:hover {
            background-color: #374151 !important;
            color: white !important;
        }
        
        /* Mejoras específicas para Safari y Chrome */
        select::-webkit-scrollbar {
            width: 8px;
        }
        
        select::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        select::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 4px;
        }
        
        /* Para Firefox */
        select {
            scrollbar-width: thin;
            scrollbar-color: #6366f1 #1f2937;
        }
        
        /* Grid responsivo */
        .habits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .habits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-purple-600 font-display overflow-x-hidden" x-data="habitApp()" 
      x-init="$nextTick(() => { document.body.classList.add('loaded') })"
      style="opacity: 0; transform: translateY(20px); transition: all 0.6s ease;"
      x-transition:enter="transition ease-out duration-1000"
      x-transition:enter-start="opacity-0 transform translate-y-8"
      x-transition:enter-end="opacity-100 transform translate-y-0">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20 animate-slide-down">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3 animate-bounce-subtle">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg 
                                transform hover:scale-110 hover:rotate-6 transition-all duration-300 hover:shadow-2xl animate-pulse-glow">
                        <span class="text-lg font-black text-white">M</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white hover:text-motiveo-accent transition-colors duration-300 animate-text-glow">MOTIVEO</h1>
                </div>

                <!-- User Level & XP -->
                <div class="flex items-center space-x-4 animate-fade-in-right">
                    <div class="hidden sm:flex items-center space-x-3">
                        <div class="bg-motiveo-warning text-white px-3 py-1 rounded-full text-sm font-bold 
                                    hover:scale-105 hover:bg-motiveo-warning/80 transition-all duration-300 animate-wiggle-on-hover" 
                             x-text="`NIVEL ${userStats.level}`"
                             @mouseenter="$el.classList.add('animate-wiggle')"
                             @mouseleave="$el.classList.remove('animate-wiggle')">
                            NIVEL {{ auth()->user()->level ?? 1 }}
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full transition-all duration-1000 animate-progress-fill" 
                                     :style="`width: ${userStats.progress}%`"
                                     style="width: {{ auth()->user()->getLevelProgress() ?? 0 }}%"></div>
                            </div>
                            <span class="text-white text-sm animate-number-count" x-text="`${userStats.xp}/${userStats.next_level_xp} XP`">
                                {{ auth()->user()->xp ?? 0 }}/{{ auth()->user()->getXpForNextLevel() ?? 100 }} XP
                            </span>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-motiveo-pink to-red-400 rounded-full flex items-center justify-center
                                    hover:scale-110 transition-all duration-300 hover:shadow-lg animate-float">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white/80 hover:text-white text-sm hover:scale-105 transition-all duration-300 
                                                         hover:bg-white/10 px-3 py-1 rounded-lg">Salir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="opacity-0 transform translate-y-2 scale-95 rotate-1"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100 rotate-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100 rotate-0"
         x-transition:leave-end="opacity-0 transform translate-y-2 scale-95 rotate-1"
         class="fixed top-4 right-4 bg-motiveo-success text-white px-6 py-3 rounded-lg shadow-2xl z-50 
                animate-bounce-gentle border-2 border-white/20 backdrop-blur-sm"
         x-text="notification.message">
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12">
        <!-- Título y Filtros -->
        <div class="flex justify-between items-center mb-12 animate-slide-up">
            <div class="animate-fade-in-left">
                <h2 class="text-3xl font-bold text-white mb-2 animate-text-shine">Mis Hábitos</h2>
                <p class="text-white/60 animate-fade-in-delayed">Gestiona tus hábitos diarios de forma organizada</p>
            </div>
            <div class="flex space-x-3 animate-fade-in-right-delayed">
                <button @click="showCreateModal = true" 
                        class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white px-6 py-3 rounded-xl font-semibold 
                               hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 
                               animate-pulse-button group overflow-hidden relative">
                    <span class="relative z-10 flex items-center">
                        <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>Nuevo Hábito
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-motiveo-secondary to-motiveo-primary opacity-0 
                                group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
                <button @click="loadUserHabits()" 
                        class="bg-white/10 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300
                               transform hover:scale-105 hover:rotate-3 animate-spin-on-hover group">
                    <i class="fas fa-sync-alt group-hover:animate-spin"></i>
                </button>
                <button @click="debugHabits()" 
                        class="bg-red-500/80 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-red-500 transition-all duration-300
                               transform hover:scale-105 hover:shadow-red-500/50 hover:shadow-lg animate-bug-wiggle">
                    <i class="fas fa-bug mr-1 animate-wiggle"></i>Debug
                </button>
            </div>
        </div>

        <!-- Grid de Hábitos -->
        <div class="habits-grid mb-24" x-show="userHabits.length > 0">
            <template x-for="(habit, index) in userHabits" :key="habit.id">
                <div class="habit-card bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 cursor-pointer 
                           hover:border-motiveo-primary/50 transition-all duration-500 mb-6 transform hover:scale-105 
                           hover:-translate-y-2 hover:shadow-2xl hover:shadow-motiveo-primary/20 animate-card-appear group
                           hover:bg-white/15 animate-float-delayed"
                     :class="(habit.today_completed || habit.status === 'completed') ? 'habit-completed animate-completed-glow' : 'habit-pending'"
                     @click="expandHabit(habit)"
                     title="Haz clic para ver detalles del hábito"
                     :style="`animation-delay: ${index * 0.1}s`"
                     x-transition:enter="transition ease-out duration-700 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    <!-- Header de la tarjeta -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-15 h-15 rounded-xl flex items-center justify-center transition-all duration-300
                                        group-hover:scale-110 group-hover:rotate-6 animate-icon-bounce"
                                 :class="getCategoryStyle(habit.categoria)">
                                <span class="text-xl" x-html="getHabitIcon(habit)"></span>
                            </div>
                            <div class="transform group-hover:translate-x-1 transition-transform duration-300">
                                <h3 class="text-white font-bold text-lg group-hover:text-motiveo-accent transition-colors duration-300" 
                                    x-text="habit.nombre"></h3>
                                <p class="text-white/60 text-sm capitalize animate-text-shimmer" x-text="habit.categoria"></p>
                            </div>
                        </div>
                        
                        <!-- Estado visual -->
                        <div class="flex flex-col items-end">
                            <div class="w-3 h-3 rounded-full mb-2 animate-status-pulse transition-all duration-300"
                                 :class="habit.is_completed ? 'bg-motiveo-success animate-success-pulse' : 'bg-motiveo-warning animate-warning-pulse'"></div>
                            <span class="text-xs text-white/60 group-hover:text-white/80 transition-colors duration-300" 
                                  x-text="habit.is_completed ? 'Completado' : 'Pendiente'"></span>
                        </div>
                    </div>

                    <!-- Descripción breve -->
                    <p class="text-white/80 text-sm mb-6 line-clamp-2 group-hover:text-white transition-colors duration-300" 
                       x-text="habit.descripcion || 'Descripción del hábito'"></p>

                    <!-- Stats -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4 text-sm">
                            <span class="text-motiveo-success flex items-center group-hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-fire mr-1 animate-fire-flicker"></i>
                                <span x-text="`${habit.dias_racha} días`" class="animate-number-count"></span>
                            </span>
                            <span class="text-motiveo-accent flex items-center group-hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-star mr-1 animate-star-twinkle"></i>
                                <span x-text="`${habit.xp_ganada || 0} XP`" class="animate-number-count"></span>
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-white/60 group-hover:text-white/80 transition-colors duration-300">
                            <i class="fas fa-calendar mr-1 animate-calendar-flip"></i>
                            <span>Día <span x-text="habit.dias_activo || 1" class="animate-number-count"></span></span>
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <button @click.stop="habit.is_completed ? handleHabitAction(habit.id, 'undo') : handleHabitAction(habit.id, 'complete')"
                            :disabled="false"
                            :class="habit.is_completed 
                                ? 'bg-motiveo-success/20 text-motiveo-success border-motiveo-success/30 hover:bg-motiveo-warning/20 hover:text-motiveo-warning hover:border-motiveo-warning' 
                                : 'bg-motiveo-warning/20 text-motiveo-warning border-motiveo-warning hover:bg-motiveo-warning hover:text-white'"
                            class="w-full py-3 px-4 rounded-xl font-semibold transition-all duration-300 border-2 flex items-center justify-center
                                   transform hover:scale-105 hover:-translate-y-1 hover:shadow-lg group-button overflow-hidden relative">
                        <template x-if="habit.is_completed">
                            <div class="flex items-center relative z-10">
                                <i class="fas fa-undo mr-2 group-button-hover:animate-spin-reverse"></i>
                                <span class="animate-text-typing">Deshacer Completado</span>
                            </div>
                        </template>
                        <template x-if="!habit.is_completed">
                            <div class="flex items-center relative z-10">
                                <i class="fas fa-play mr-2 group-button-hover:translate-x-1 transition-transform duration-300"></i>
                                <span class="animate-text-typing">Iniciar Hábito</span>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
                                    -translate-x-full group-button-hover:translate-x-full transition-transform duration-700"></div>
                    </button>
                </div>
            </template>
        </div>

        <!-- Estado vacío -->
        <div x-show="userHabits.length === 0" 
             x-transition:enter="transition ease-out duration-800 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="text-center py-20 mb-24 mx-4 sm:mx-6 lg:mx-8 animate-fade-in-up">
            <div class="text-6xl mb-6 text-motiveo-primary animate-bounce-gentle">
                <i class="fas fa-bullseye animate-icon-bounce"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 animate-text-glow">¡Comienza tu viaje!</h3>
            <p class="text-white/60 mb-8 max-w-lg mx-auto animate-text-shimmer">
                Aún no tienes hábitos creados. Comienza creando tu primer hábito y da el primer paso hacia una mejor versión de ti mismo.
            </p>
            <button @click="showCreateModal = true" 
                    class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white px-8 py-3 rounded-xl font-semibold 
                           hover:shadow-2xl transition-all duration-500 transform hover:scale-110 hover:-translate-y-2 
                           animate-pulse-button group overflow-hidden relative">
                <span class="relative z-10 flex items-center">
                    <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>Crear Mi Primer Hábito
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-motiveo-secondary to-motiveo-primary opacity-0 
                            group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
        </div>

        <!-- Sección de Hábitos Sugeridos -->
        <div class="mt-24 mb-8 mx-4 sm:mx-6 lg:mx-8 animate-slide-up">
            <div class="flex justify-between items-center mb-8">
                <div class="animate-fade-in-left">
                    <h3 class="text-2xl font-bold text-white mb-2 animate-text-shine">Hábitos Sugeridos</h3>
                    <p class="text-white/60 animate-text-shimmer">Descubre nuevos hábitos que podrían interesarte</p>
                </div>
                <div class="flex space-x-3 animate-fade-in-right-delayed">
                    <button @click.stop="openHabitExplorer()" 
                            class="bg-motiveo-primary/80 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-motiveo-primary 
                                   transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                        <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>Explorar Todos ({{ $totalHabits ?? 55 }})
                    </button>
                    <button @click="refreshData()" 
                            :disabled="isRefreshing"
                            class="bg-white/10 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-white/20 
                                   transition-all duration-300 transform hover:scale-105 group disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-refresh mr-2 transition-transform duration-300" 
                           :class="isRefreshing ? 'animate-spin' : 'group-hover:animate-spin'"></i>
                        <span x-text="isRefreshing ? 'Actualizando...' : 'Actualizar'"></span>
                    </button>
                </div>
            </div>

            <!-- Grid de Sugerencias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16" x-show="suggestions.popular && suggestions.popular.length > 0">
                <template x-for="(suggestion, index) in suggestions.popular" :key="suggestion.id">
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 hover:bg-white/10 
                               transition-all duration-500 cursor-pointer transform hover:scale-105 hover:-translate-y-2 
                               hover:shadow-xl hover:shadow-motiveo-primary/20 animate-card-appear group"
                         :style="`animation-delay: ${index * 0.1}s`"
                         @click="adoptSuggestion(suggestion)"
                         x-transition:enter="transition ease-out duration-600 transform"
                         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-14 h-14 rounded-lg flex items-center justify-center group-hover:scale-110 
                                        group-hover:rotate-6 transition-all duration-300 animate-icon-bounce"
                                 :class="getCategoryStyle(suggestion.categoria)">
                                <span class="text-lg" x-html="getCategoryIcon(suggestion.categoria)"></span>
                            </div>
                            <div class="transform group-hover:translate-x-1 transition-transform duration-300">
                                <h4 class="text-white font-semibold text-sm group-hover:text-motiveo-accent transition-colors duration-300" 
                                    x-text="suggestion.name"></h4>
                                <p class="text-white/60 text-xs capitalize animate-text-shimmer" x-text="suggestion.categoria"></p>
                            </div>
                        </div>
                        <p class="text-white/70 text-xs mb-3 line-clamp-2 group-hover:text-white transition-colors duration-300" 
                           x-text="suggestion.description"></p>
                        <button class="w-full bg-motiveo-accent/20 text-motiveo-accent py-2 px-3 rounded-lg text-xs font-medium 
                                       hover:bg-motiveo-accent hover:text-white transition-all duration-300 transform hover:scale-105
                                       group-hover:shadow-lg overflow-hidden relative">
                            <span class="relative z-10 flex items-center justify-center">
                                <i class="fas fa-plus mr-1 group-hover:rotate-90 transition-transform duration-300"></i>Agregar
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
                                        -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Categorías de sugerencias -->
            <div class="mt-16 mb-12 animate-slide-up">
                <h4 class="text-lg font-semibold text-white mb-6 animate-text-glow">Explorar por Categoría</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <template x-for="([category, habits], index) in Object.entries(suggestions.by_category || {})" :key="category">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 hover:bg-white/10 
                                   transition-all duration-500 cursor-pointer transform hover:scale-105 hover:-translate-y-2 
                                   hover:shadow-xl hover:shadow-motiveo-primary/20 animate-card-appear group"
                             :style="`animation-delay: ${index * 0.1}s`"
                             @click="showCategoryDetails(category, habits)"
                             x-transition:enter="transition ease-out duration-600 transform"
                             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                            <div class="text-center">
                                <div class="w-20 h-14 rounded-lg flex items-center justify-center mx-auto mb-2 
                                           group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 animate-icon-bounce"
                                     :class="getCategoryStyle(category)">
                                    <span class="text-xl" x-html="getCategoryIcon(category)"></span>
                                </div>
                                <h5 class="text-white font-medium text-sm capitalize group-hover:text-motiveo-accent 
                                          transition-colors duration-300" x-text="category"></h5>
                                <p class="text-white/60 text-xs animate-number-count" x-text="`${habits.length} hábitos`"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
                             </div>
                    
                            <div class= "max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <h2 class="text-xl font-bold text-white">
                                <span x-show="userHabits.length === 0">¡Comienza tu Viaje!</span>
                            </h2>
                        </div>

                    <div x-show="userHabits.length === 0" class="mb-4 p-4 bg-motiveo-primary/10 rounded-xl border border-motiveo-primary/20">
                        <div class="flex items-center space-x-2 text-motiveo-accent mb-2">
                            <i class="fas fa-star text-lg"></i>
                            <span class="font-semibold">¡Bienvenido a Motiveo!</span>
                        </div>
                        <p class="text-white/80 text-sm">
                            Selecciona algunos hábitos populares para comenzar tu transformación personal.
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-white mb-6">    
                    <span x-show="userHabits.length > 0">Más Hábitos Populares</span>
                    </h2>
                        <template x-for="suggestion in suggestions.popular" :key="suggestion.id">
                            <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all border border-white/5 hover:border-white/20 mb-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="text-2xl" x-html="suggestion.icon"></div>
                                        <div class="flex-1">
                                            <h3 class="text-white font-semibold" x-text="suggestion.name"></h3>
                                            <p class="text-white/60 text-sm line-clamp-2" x-text="suggestion.description"></p>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <span class="text-xs bg-motiveo-warning/20 text-motiveo-warning px-2 py-1 rounded-full" x-text="suggestion.categoria"></span>
                                                <span class="text-xs text-white/50" x-text="`${suggestion.popularity} personas lo hacen`"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="adoptSuggestion(suggestion)"
                                            :disabled="isAdopting"
                                            class="bg-motiveo-primary hover:bg-motiveo-primary/80 disabled:bg-gray-500 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all flex items-center space-x-1">
                                        <span x-show="!isAdopting"><i class="fas fa-heart"></i></span>
                                        <span x-show="isAdopting"><i class="fas fa-spinner fa-spin"></i></span>
                                        <span x-text="isAdopting ? 'Agregando...' : 'Me gusta'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
    </div>  
                        <!-- Mensaje cuando no hay sugerencias -->
                        <div x-show="suggestions.popular && suggestions.popular.length === 0" 
                             class="text-center py-8">
                            <div class="text-6xl mb-4 text-motiveo-success">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3 class="text-white font-semibold mb-2">¡Excelente progreso!</h3>
                            <p class="text-white/60 text-sm">
                                Ya tienes muchos hábitos populares. Explora las categorías below o crea un hábito personalizado.
                            </p>
                        </div>
                    </div>

                    <!-- Categorías de sugerencias -->
                    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12">
                        <h3 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-tags mr-2"></i>
                            Por Categorías
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(habits, category) in suggestions.by_category" :key="category">
                                <div class="bg-white/5 rounded-lg p-3 hover:bg-white/10 transition-all cursor-pointer"
                                     @click="showCategoryDetails(category, habits)">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg" x-html="getCategoryIcon(category)"></span>
                                        <div>
                                            <div class="text-white text-sm font-medium capitalize" x-text="category"></div>
                                            <div class="text-white/60 text-xs" x-text="`${habits.length} opciones`"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('formulario_habito.show') }}" 
                           class="text-motiveo-accent hover:text-motiveo-accent/80 text-sm font-medium inline-flex items-center space-x-1">
                            <i class="fas fa-palette"></i>
                            <span>¿No encuentras lo que buscas? Crea tu hábito personalizado</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Center Panel - Hábitos Activos -->
            <div class="space-y-8 mt-24 mb-16 mx-4 sm:mx-6 lg:mx-8">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                            <i class="fas fa-bullseye text-white text-sm"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Hábitos de Hoy</h2>
                    </div>

                    <div class="space-y-8" x-show="activeHabits.length > 0">
                        <template x-for="habit in activeHabits" :key="habit.id">
                            <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl" x-html="getHabitIcon(habit)"></span>
                                        <div>
                                            <h3 class="text-white font-semibold" x-text="habit.nombre"></h3>
                                            <p class="text-white/60 text-xs" x-text="`Día ${habit.current_day || 1} de ${habit.duration_days || 30}`"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-motiveo-warning text-sm" x-text="habit.frequency"></span>
                                        <p class="text-white/60 text-xs" x-text="`${habit.remaining_days || 29} días restantes`"></p>
                                    </div>
                                </div>
                                
                                <!-- Barra de progreso -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-xs text-white/60 mb-1">
                                        <span>Progreso</span>
                                        <span x-text="`${habit.progress_percentage || 0}%`"></span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-motiveo-success to-emerald-400 h-2 rounded-full transition-all duration-500"
                                             :style="`width: ${habit.progress_percentage || 0}%`"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="text-motiveo-success flex items-center">
                                            <i class="fas fa-fire mr-1"></i>
                                            <span x-text="`${habit.dias_racha} días`"></span>
                                        </span>
                                        <span class="text-white/60">días</span>
                                    </div>
                                    <button @click="completeHabit(habit)"
                                            :disabled="habit.today_completed || !habit.can_complete"
                                            :class="(habit.today_completed || !habit.can_complete) ? 'bg-gray-500' : 'bg-motiveo-success hover:bg-motiveo-success/80'"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all">
                                        <span x-text="habit.today_completed ? 'Completado' : 'Completar (+20 XP)'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="activeHabits.length === 0" class="text-center py-8">
                        <div class="text-6xl mb-4 text-motiveo-success">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="text-white font-semibold mb-2">¡Todos los hábitos completados!</h3>
                        <p class="text-white/60 text-sm">Excelente trabajo. ¡Sigue así!</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Completados y Estadísticas -->
            <div class="space-y-8 mt-24 mb-16 mx-4 sm:mx-6 lg:mx-8">
                <!-- Completados Hoy -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Completados Hoy</h2>
                    </div>

                    <div class="space-y-4" x-show="completedHabits.length > 0">
                        <template x-for="habit in completedHabits" :key="habit.id">
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <span x-html="getHabitIcon(habit)"></span>
                                    <div>
                                        <div class="text-white text-sm" x-text="habit.nombre"></div>
                                        <div class="text-white/60 text-xs" x-text="`Día ${habit.current_day || 1} de ${habit.duration_days || 30} - ${habit.completed_at || ''}`"></div>
                                    </div>
                                </div>
                                <div class="text-motiveo-success text-xs font-medium">+20 XP</div>
                            </div>
                        </template>
                    </div>

                    <div x-show="completedHabits.length === 0" class="text-center py-4">
                        <p class="text-white/60 text-sm">Aún no hay hábitos completados hoy</p>
                    </div>
                </div>

                <!-- Progreso por Categoría -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 mt-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Estadísticas</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="text-center p-6 bg-white/5 rounded-lg">
                            <div class="text-3xl font-black text-motiveo-warning mb-1" x-text="userStats.xp">
                                {{ auth()->user()->xp ?? 0 }}
                            </div>
                            <div class="text-white/60 text-sm">Puntos de Experiencia</div>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div class="text-center p-4 bg-white/5 rounded-lg">
                                <div class="text-xl font-bold text-motiveo-success" x-text="totalHabits">
                                    {{ auth()->user()->habits()->count() ?? 0 }}
                                </div>
                                <div class="text-white/60 text-xs">Hábitos Total</div>
                            </div>
                            <div class="text-center p-3 bg-white/5 rounded-lg">
                                <div class="text-xl font-bold text-motiveo-accent" x-text="completedHabits.length">0</div>
                                <div class="text-white/60 text-xs">Hoy Completados</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear Nuevo Hábito -->
    <div x-show="showCreateModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto"
         @click.self="showCreateModal = false">
        
        <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
            <div x-show="showCreateModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="showCreateModal = false"
                 class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl max-h-[85vh] overflow-hidden">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-8 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center">
                            <i class="fas fa-magic text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Hábito</h2>
                            <p class="text-sm text-gray-600" x-text="`Paso ${createForm.step} de 5`"></p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="px-8 pt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary h-2 rounded-full transition-all duration-300"
                             :style="`width: ${(createForm.step / 5) * 100}%`"></div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="overflow-y-auto" style="max-height: calc(85vh - 140px);">
                    <form @submit.prevent="submitCreateForm()" class="p-8">
                        
                        <!-- Paso 1: Información Básica -->
                        <div x-show="createForm.step === 1" class="space-y-8">
                            <div class="text-center mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Qué hábito quieres desarrollar?</h3>
                                <p class="text-gray-600">Comencemos con la información básica de tu nuevo hábito.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del hábito</label>
                                <input type="text" 
                                       x-model="createForm.name"
                                       placeholder="Ej: Hacer ejercicio, Leer 30 minutos, Meditar..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                <textarea x-model="createForm.description"
                                          placeholder="Describe brevemente en qué consiste este hábito..."
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"></textarea>
                            </div>
                        </div>

                        <!-- Paso 2: Frecuencia y Categoría -->
                        <div x-show="createForm.step === 2" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Con qué frecuencia lo harás?</h3>
                                <p class="text-gray-600">Elige la frecuencia y categoría que mejor se adapte a tu objetivo.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Frecuencia</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.frequency" value="diario" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.frequency === 'diario' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-calendar-day text-blue-500"></i></div>
                                                <div class="font-semibold">Diario</div>
                                                <div class="text-sm text-gray-600">Todos los días</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.frequency" value="semanal" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.frequency === 'semanal' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-chart-bar text-purple-500"></i></div>
                                                <div class="font-semibold">Semanal</div>
                                                <div class="text-sm text-gray-600">Una vez por semana</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Categoría</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.category" value="salud" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.category === 'salud' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-heartbeat text-red-500"></i></div>
                                                <div class="font-semibold">Salud</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.category" value="productividad" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.category === 'productividad' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-briefcase text-blue-500"></i></div>
                                                <div class="font-semibold">Productividad</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.category" value="bienestar" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.category === 'bienestar' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-smile text-yellow-500"></i></div>
                                                <div class="font-semibold">Bienestar</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.category" value="aprendizaje" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.category === 'aprendizaje' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-book text-green-500"></i></div>
                                                <div class="font-semibold">Aprendizaje</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Duración</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.duration_days" value="21" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.duration_days === 21 ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-clock text-orange-500"></i></div>
                                                <div class="font-semibold">21 días</div>
                                                <div class="text-sm text-gray-600">Rápido</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.duration_days" value="30" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.duration_days === 30 ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-calendar-alt text-green-500"></i></div>
                                                <div class="font-semibold">30 días</div>
                                                <div class="text-sm text-gray-600">Recomendado</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.duration_days" value="60" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.duration_days === 60 ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-bullseye text-purple-500"></i></div>
                                                <div class="font-semibold">60 días</div>
                                                <div class="text-sm text-gray-600">Desafío</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" x-model="createForm.duration_days" value="90" class="sr-only">
                                        <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                             :class="createForm.duration_days === 90 ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="text-center">
                                                <div class="text-2xl mb-2"><i class="fas fa-trophy text-yellow-500"></i></div>
                                                <div class="font-semibold">90 días</div>
                                                <div class="text-sm text-gray-600">Experto</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 3: Motivación -->
                        <div x-show="createForm.step === 3" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Qué te motiva a crear este hábito?</h3>
                                <p class="text-gray-600">Entender tu motivación te ayudará a mantener la constancia.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tu motivación</label>
                                <textarea x-model="createForm.motivation"
                                          placeholder="Ej: Quiero sentirme más saludable, mejorar mi concentración, desarrollar una nueva habilidad..."
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"
                                          required></textarea>
                            </div>
                        </div>

                        <!-- Paso 4: Recompensa -->
                        <div x-show="createForm.step === 4" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cómo te recompensarás?</h3>
                                <p class="text-gray-600">Una recompensa personal te ayudará a mantener la motivación.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tu recompensa</label>
                                <textarea x-model="createForm.reward"
                                          placeholder="Ej: Ver una película, comprar algo especial, salir con amigos, un día de descanso..."
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"></textarea>
                            </div>
                        </div>

                        <!-- Paso 5: Fecha de inicio -->
                        <div x-show="createForm.step === 5" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cuándo empezarás?</h3>
                                <p class="text-gray-600">Elige una fecha para comenzar tu nuevo hábito.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio</label>
                                <input type="date" 
                                       x-model="createForm.start_date"
                                       :min="new Date().toISOString().split('T')[0]"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"
                                       required>
                            </div>

                            <!-- Resumen -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Resumen de tu hábito:</h4>
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Nombre:</span> <span x-text="createForm.name"></span></p>
                                    <p><span class="font-medium">Frecuencia:</span> <span x-text="createForm.frequency"></span></p>
                                    <p><span class="font-medium">Categoría:</span> <span x-text="createForm.category"></span></p>
                                    <p><span class="font-medium">Inicio:</span> <span x-text="createForm.start_date"></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                            <button type="button" 
                                    @click="createForm.step > 1 ? createForm.step-- : (showCreateModal = false)"
                                    class="px-6 py-2 text-gray-600 hover:text-gray-800 font-medium">
                                <span x-text="createForm.step > 1 ? 'Anterior' : 'Cancelar'"></span>
                            </button>
                            
                            <div class="flex space-x-3">
                                <button type="button" 
                                        x-show="createForm.step < 5"
                                        @click="nextStep()"
                                        class="px-6 py-2 bg-motiveo-primary text-white rounded-lg hover:bg-motiveo-primary/90 font-medium">
                                    Siguiente
                                </button>
                                
                                <button type="submit" 
                                        x-show="createForm.step === 5"
                                        class="px-6 py-2 bg-gradient-to-r from-motiveo-success to-emerald-500 text-white rounded-lg hover:shadow-lg font-medium">
                                    <i class="fas fa-rocket mr-1"></i>Crear Hábito
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Hábito -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="showEditModal = false">
        
        <div x-show="showEditModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="showEditModal = false"
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.stop>
            
            <form @submit.prevent="updateHabit()" class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900"><i class="fas fa-edit mr-2"></i>Editar Hábito</h2>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Formulario de Edición -->
                <div class="space-y-6">
                    <!-- Nombre del Hábito -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre del Hábito
                        </label>
                        <input type="text" 
                               x-model="editForm.nombre"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                               placeholder="Ej: Hacer ejercicio, Leer 30 minutos, Meditar..."
                               required>
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Categoría
                        </label>
                        <select x-model="editForm.categoria"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                                required>
                            <option value="">Selecciona una categoría</option>
                            <option value="salud"><i class="fas fa-heartbeat mr-2"></i>Salud</option>
                            <option value="productividad"><i class="fas fa-briefcase mr-2"></i>Productividad</option>
                            <option value="bienestar"><i class="fas fa-smile mr-2"></i>Bienestar</option>
                            <option value="aprendizaje"><i class="fas fa-book mr-2"></i>Aprendizaje</option>
                        </select>
                    </div>

                    <!-- Duración -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Duración del Desafío
                        </label>
                        <select x-model="editForm.duration_days"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                                required>
                            <option value="">Selecciona duración</option>
                            <option value="21">21 días - Hábito básico</option>
                            <option value="30">30 días - Desafío estándar</option>
                            <option value="60">60 días - Transformación profunda</option>
                            <option value="90">90 días - Cambio permanente</option>
                        </select>
                    </div>

                    <!-- Motivación -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ¿Por qué quieres mantener este hábito?
                        </label>
                        <textarea x-model="editForm.motivation"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                                  rows="3"
                                  placeholder="Describe qué te motiva a mantener este hábito..."></textarea>
                    </div>

                    <!-- Recompensa -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ¿Cómo te recompensarás? (Opcional)
                        </label>
                        <input type="text" 
                               x-model="editForm.reward"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                               placeholder="Ej: Ver una película, comprar algo especial...">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <button type="button" 
                            @click="showEditModal = false"
                            class="px-6 py-2 text-gray-600 hover:text-gray-800 font-medium">
                        Cancelar
                    </button>
                    
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-motiveo-primary to-blue-600 text-white rounded-lg hover:shadow-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Actualizar Hábito
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de hábito expandido -->
    <div x-show="expandedHabit" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="closeHabitDetails()">
        
        <div x-show="expandedHabit"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-gradient-to-br from-gray-900/95 to-gray-800/95 backdrop-blur-lg rounded-2xl border border-motiveo-primary/20 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            
            <!-- Header del Modal -->
            <div class="p-6 border-b border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-motiveo-primary to-purple-600 flex items-center justify-center">
                            <span x-text="expandedHabit?.icon || '<i class=\"fas fa-bullseye\"></i>'" class="text-2xl"></span>
                        </div>
                        <div>
                            <h3 x-text="expandedHabit?.name" class="text-xl font-bold text-white"></h3>
                            <p x-text="expandedHabit?.category" class="text-motiveo-primary capitalize"></p>
                        </div>
                    </div>
                    <button @click="closeHabitDetails()" 
                            class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Estado del hábito -->
                <div class="mt-4 flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full"
                             :class="expandedHabit?.is_completed ? 'bg-motiveo-success animate-pulse' : 'bg-motiveo-warning'"></div>
                        <span class="text-sm text-gray-300" 
                              x-text="expandedHabit?.is_completed ? 'Completado hoy' : 'Pendiente'"></span>
                    </div>
                    <div class="text-sm text-gray-400">
                        <span x-text="expandedHabit?.current_streak || 0"></span> días consecutivos
                    </div>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-6 space-y-6">
                <!-- Descripción -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-2"><i class="fas fa-file-alt mr-2"></i>Descripción</h4>
                    <p x-text="expandedHabit?.description || 'Sin descripción disponible'" 
                       class="text-gray-300 leading-relaxed"></p>
                </div>

                <!-- Progreso visual -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-3"><i class="fas fa-chart-line mr-2"></i>Progreso</h4>
                    <div class="bg-gray-800/50 rounded-lg p-4">
                        <div class="flex justify-between text-sm text-gray-400 mb-2">
                            <span>Días completados</span>
                            <span x-text="`${expandedHabit?.completed_days || 0} / ${expandedHabit?.duration_days || 30}`"></span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-motiveo-primary to-motiveo-success h-3 rounded-full transition-all duration-300"
                                 :style="`width: ${expandedHabit ? (expandedHabit.completed_days || 0) / (expandedHabit.duration_days || 30) * 100 : 0}%`"></div>
                        </div>
                    </div>
                </div>

                <!-- Guía paso a paso -->
                <div x-show="expandedHabit && !(expandedHabit.today_completed || expandedHabit.status === 'completed')">
                    <h4 class="text-lg font-semibold text-white mb-3"><i class="fas fa-route mr-2"></i>Guía paso a paso</h4>
                    <div class="space-y-3">
                        <template x-for="(step, index) in getHabitSteps(expandedHabit)" :key="index">
                            <div class="flex items-start space-x-3 p-3 rounded-lg bg-gray-800/30 border border-gray-700/50 hover:border-motiveo-primary/30 transition-colors">
                                <div class="w-6 h-6 rounded-full bg-motiveo-primary/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span x-text="index + 1" class="text-xs font-bold text-motiveo-primary"></span>
                                </div>
                                <div class="flex-1">
                                    <p x-text="step" class="text-gray-300 text-sm leading-relaxed"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Estadísticas adicionales -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-800/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-motiveo-success" x-text="expandedHabit?.current_streak || 0"></div>
                        <div class="text-sm text-gray-400">Racha actual</div>
                    </div>
                    <div class="bg-gray-800/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-motiveo-warning" x-text="expandedHabit?.best_streak || 0"></div>
                        <div class="text-sm text-gray-400">Mejor racha</div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex flex-col space-y-3 pt-4 border-t border-gray-700/50">
                    <template x-if="expandedHabit && !expandedHabit.is_completed">
                        <button @click="handleHabitAction(expandedHabit.id, 'complete')"
                                class="w-full py-3 px-4 bg-gradient-to-r from-motiveo-success to-emerald-500 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                            <i class="fas fa-check mr-2"></i>Marcar como Completado
                        </button>
                    </template>
                    
                    <template x-if="expandedHabit && expandedHabit.is_completed">
                        <button @click="handleHabitAction(expandedHabit.id, 'undo')"
                                class="w-full py-3 px-4 bg-gradient-to-r from-motiveo-warning to-orange-500 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                            <i class="fas fa-undo mr-2"></i>Deshacer Completado
                        </button>
                    </template>
                    
                    <button @click="showEditHabit(expandedHabit)"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-edit mr-2"></i>Editar Hábito
                    </button>
                    
                    <button @click="confirmDeleteHabit(expandedHabit)"
                            class="w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-trash mr-2"></i>Eliminar Hábito
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Explorador de Hábitos -->
    <div x-show="showHabitExplorer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="showHabitExplorer = false">

        <div x-show="showHabitExplorer"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-gradient-to-br from-motiveo-dark/95 to-gray-900/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-6xl max-h-[85vh] border border-white/10 overflow-hidden"
             @click.stop>
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-white/10">
                <div>
                    <h2 class="text-2xl font-bold text-white">Explorador de Hábitos</h2>
                    <p class="text-white/60 mt-1">Descubre más de 50 hábitos organizados por categorías</p>
                </div>
                <button @click.stop="showHabitExplorer = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Filters and Search -->
            <div class="p-6 border-b border-white/10">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <div class="relative">
                            <input x-model="explorerFilters.search" 
                                   @input="searchHabits()"
                                   @click.stop
                                   type="text" 
                                   placeholder="Buscar hábitos..." 
                                   class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 pl-10 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-motiveo-primary">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-white/50"></i>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="md:w-48 relative" x-data="{ isOpen: false }">
                        <button @click="isOpen = !isOpen" 
                                @click.stop
                                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-motiveo-primary appearance-none cursor-pointer flex items-center justify-between">
                            <span x-show="explorerFilters.category === 'all'" class="flex items-center">
                                <i class="fas fa-star mr-2"></i>Todas las categorías
                            </span>
                            <span x-show="explorerFilters.category === 'salud'" class="flex items-center">
                                <i class="fas fa-heartbeat mr-2"></i>Salud
                            </span>
                            <span x-show="explorerFilters.category === 'productividad'" class="flex items-center">
                                <i class="fas fa-briefcase mr-2"></i>Productividad
                            </span>
                            <span x-show="explorerFilters.category === 'bienestar'" class="flex items-center">
                                <i class="fas fa-smile mr-2"></i>Bienestar
                            </span>
                            <span x-show="explorerFilters.category === 'aprendizaje'" class="flex items-center">
                                <i class="fas fa-book mr-2"></i>Aprendizaje
                            </span>
                            <span x-show="explorerFilters.category === 'finanzas'" class="flex items-center">
                                <i class="fas fa-dollar-sign mr-2"></i>Finanzas
                            </span>
                            <span x-show="explorerFilters.category === 'relaciones'" class="flex items-center">
                                <i class="fas fa-heart mr-2"></i>Relaciones
                            </span>
                            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="isOpen" 
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 w-full mt-1 bg-gray-800 border border-white/20 rounded-xl shadow-lg max-h-60 overflow-auto">
                            <button @click="explorerFilters.category = 'all'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-star mr-2"></i>Todas las categorías
                            </button>
                            <button @click="explorerFilters.category = 'salud'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-heartbeat mr-2"></i>Salud
                            </button>
                            <button @click="explorerFilters.category = 'productividad'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-briefcase mr-2"></i>Productividad
                            </button>
                            <button @click="explorerFilters.category = 'bienestar'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-smile mr-2"></i>Bienestar
                            </button>
                            <button @click="explorerFilters.category = 'aprendizaje'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-book mr-2"></i>Aprendizaje
                            </button>
                            <button @click="explorerFilters.category = 'finanzas'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-dollar-sign mr-2"></i>Finanzas
                            </button>
                            <button @click="explorerFilters.category = 'relaciones'; searchHabits(); isOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-heart mr-2"></i>Relaciones
                            </button>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="md:w-48 relative" x-data="{ isSortOpen: false }">
                        <button @click="isSortOpen = !isSortOpen" 
                                @click.stop
                                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-motiveo-primary appearance-none cursor-pointer flex items-center justify-between">
                            <span x-show="explorerFilters.sort === 'popularity'" class="flex items-center">
                                <i class="fas fa-star mr-2"></i>Más populares
                            </span>
                            <span x-show="explorerFilters.sort === 'name'" class="flex items-center">
                                <i class="fas fa-sort-alpha-down mr-2"></i>Alfabético
                            </span>
                            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Sort Dropdown Menu -->
                        <div x-show="isSortOpen" 
                             @click.away="isSortOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 w-full mt-1 bg-gray-800 border border-white/20 rounded-xl shadow-lg">
                            <button @click="explorerFilters.sort = 'popularity'; searchHabits(); isSortOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-star mr-2"></i>Más populares
                            </button>
                            <button @click="explorerFilters.sort = 'name'; searchHabits(); isSortOpen = false" 
                                    class="w-full text-left px-4 py-3 text-white hover:bg-white/10 flex items-center">
                                <i class="fas fa-sort-alpha-down mr-2"></i>Alfabético
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Category Filters -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <template x-for="category in explorerCategories" :key="category.key">
                        <button @click.stop="explorerFilters.category = category.key; searchHabits()"
                                :class="explorerFilters.category === category.key ? 'bg-motiveo-primary text-white' : 'bg-white/10 text-white/70 hover:bg-white/20'"
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-all"
                                x-html="category.label">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Results -->
            <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 200px);">
                <!-- Loading State -->
                <div x-show="explorerLoading" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-motiveo-primary"></div>
                    <p class="text-white/60 mt-2">Cargando hábitos...</p>
                </div>

                <!-- Results Count -->
                <div x-show="!explorerLoading && explorerHabits.length > 0" class="mb-4">
                    <p class="text-white/70 text-sm">
                        <span x-text="explorerHabits.length"></span> hábitos encontrados
                    </p>
                </div>

                <!-- Habits Grid -->
                <div x-show="!explorerLoading && explorerHabits.length > 0" 
                     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="habit in explorerHabits" :key="habit.id">
                        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 border border-white/10 hover:border-white/20 transition-all group">
                            <!-- Habit Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="text-2xl" x-html="habit.icon || getHabitIcon(habit)"></div>
                                    <div>
                                        <div class="text-white font-medium" x-text="habit.name"></div>
                                        <div class="text-white/60 text-xs" x-text="habit.description"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-white/50">
                                    <i class="fas fa-users mr-1"></i> <span x-text="habit.popularity"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-white/70 text-xs mb-3 line-clamp-2" x-text="habit.description"></p>

                            <!-- Benefits -->
                            <div x-show="habit.benefits" class="mb-3">
                                <p class="text-white/60 text-xs">
                                    <span class="text-motiveo-success"><i class="fas fa-sparkles mr-1"></i>Beneficios:</span>
                                    <span class="line-clamp-1" x-text="habit.benefits"></span>
                                </p>
                            </div>

                            <!-- Steps Preview -->
                            <div x-show="habit.steps && habit.steps.length > 0" class="mb-3">
                                <p class="text-white/60 text-xs mb-1">
                                    <span class="text-motiveo-accent"><i class="fas fa-list-ul mr-1"></i>Pasos:</span>
                                </p>
                                <ul class="text-xs text-white/50 space-y-1">
                                    <template x-for="(step, index) in habit.steps.slice(0, 2)" :key="index">
                                        <li class="flex items-start space-x-2">
                                            <span class="text-motiveo-accent mt-0.5"><i class="fas fa-circle text-xs"></i></span>
                                            <span class="line-clamp-1" x-text="step"></span>
                                        </li>
                                    </template>
                                    <li x-show="habit.steps.length > 2" class="text-motiveo-primary text-xs">
                                        +<span x-text="habit.steps.length - 2"></span> pasos más...
                                    </li>
                                </ul>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 mt-3">
                                <button @click.stop="adoptSuggestionFromExplorer(habit)"
                                        class="flex-1 bg-motiveo-primary/20 hover:bg-motiveo-primary text-motiveo-primary hover:text-white py-2 px-3 rounded-lg text-xs font-medium transition-all group-hover:bg-motiveo-primary group-hover:text-white">
                                    <i class="fas fa-plus mr-1"></i>Agregar Hábito
                                </button>
                                <button @click.stop="showHabitDetails(habit)"
                                        class="bg-white/10 hover:bg-white/20 text-white py-2 px-3 rounded-lg text-xs font-medium transition-all">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- No Results -->
                <div x-show="!explorerLoading && explorerHabits.length === 0" class="text-center py-8">
                    <div class="text-6xl mb-4"><i class="fas fa-search text-blue-500"></i></div>
                    <h3 class="text-white font-semibold mb-2">No se encontraron hábitos</h3>
                    <p class="text-white/60 text-sm">
                        Intenta cambiar los filtros o términos de búsqueda.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-white/10 bg-white/5">
                <div class="flex justify-between items-center">
                    <p class="text-white/60 text-sm">
                        <i class="fas fa-lightbulb mr-2"></i>Explora diferentes categorías para encontrar hábitos que se adapten a tus objetivos
                    </p>
                    <button @click.stop="showHabitExplorer = false"
                            class="bg-motiveo-primary hover:bg-motiveo-primary/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function habitApp() {
            return {
                userHabits: [],
                activeHabits: [],
                completedHabits: [],
                suggestions: { popular: [], by_category: {} },
                showSuggestions: false,
                isAdopting: false, // Estado para controlar la adopción
                showCreateModal: false,
                showEditModal: false,
                createForm: {
                    step: 1,
                    name: '',
                    description: '',
                    frequency: 'diario',
                    category: 'bienestar',
                    duration_days: 30,
                    motivation: '',
                    reward: '',
                    start_date: new Date().toISOString().split('T')[0]
                },
                editForm: {
                    id: null,
                    nombre: '',
                    categoria: '',
                    duration_days: 30,
                    motivation: '',
                    reward: ''
                },
                totalHabits: 0,
                userStats: {
                    xp: {{ auth()->user()->xp ?? 0 }},
                    level: {{ auth()->user()->level ?? 1 }},
                    progress: {{ auth()->user()->getLevelProgress() ?? 0 }},
                    next_level_xp: {{ auth()->user()->getXpForNextLevel() ?? 100 }}
                },
                notification: {
                    show: false,
                    message: ''
                },
                expandedHabit: null,
                fromExplorer: false, // Para recordar si se vino del explorador
                
                // Habit Explorer
                showHabitExplorer: false,
                explorerHabits: [],
                explorerLoading: false,
                explorerFilters: {
                    search: '',
                    category: 'all',
                    sort: 'popularity'
                },
                explorerCategories: [
                    { key: 'all', label: '<i class="fas fa-star mr-2"></i>Todos' },
                    { key: 'salud', label: '<i class="fas fa-heartbeat mr-2"></i>Salud' },
                    { key: 'productividad', label: '<i class="fas fa-briefcase mr-2"></i>Productividad' },
                    { key: 'bienestar', label: '<i class="fas fa-smile mr-2"></i>Bienestar' },
                    { key: 'aprendizaje', label: '<i class="fas fa-book mr-2"></i>Aprendizaje' },
                    { key: 'finanzas', label: '<i class="fas fa-dollar-sign mr-2"></i>Finanzas' },
                    { key: 'relaciones', label: '<i class="fas fa-heart mr-2"></i>Relaciones' }
                ],

                init() {
                    this.loadUserHabits();
                    this.loadSuggestions();
                    
                    // Mostrar notificación si ganó XP por login
                    @if(session('xp_gained'))
                    this.showNotification('{{ session('xp_gained.reason') }}');
                    @endif
                },

                async loadUserHabits() {
                    try {
                        console.log('[LOADING] Cargando hábitos del usuario...');
                        const response = await fetch('/api/user-habits');
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();
                        console.log('[DATA] Datos recibidos:', data);
                        
                        this.activeHabits = data.active_habits || [];
                        this.completedHabits = data.completed_today || [];
                        this.userHabits = [...this.activeHabits, ...this.completedHabits];
                        this.totalHabits = this.userHabits.length;
                        this.userStats = data.user_stats;
                        
                        console.log('[SUCCESS] Hábitos cargados:', {
                            activos: this.activeHabits.length,
                            completados: this.completedHabits.length,
                            total: this.userHabits.length
                        });
                        
                        // Auto-mostrar sugerencias si no hay hábitos
                        if (this.userHabits.length === 0) {
                            this.showSuggestions = true;
                        }
                    } catch (error) {
                        console.error('[ERROR] Error loading habits:', error);
                        this.showNotification('Error al cargar los hábitos');
                    }
                },

                reorganizeHabits() {
                    // Separar hábitos completados y pendientes
                    const completedToday = this.userHabits.filter(h => h.today_completed || h.status === 'completed');
                    const pending = this.userHabits.filter(h => !h.today_completed && h.status !== 'completed');
                    
                    // Reorganizar: pendientes primero, completados al final
                    this.userHabits = [...pending, ...completedToday];
                    this.activeHabits = pending;
                    this.completedHabits = completedToday;
                },

                async loadSuggestions() {
                    try {
                        const response = await fetch('/api/suggestions');
                        this.suggestions = await response.json();
                    } catch (error) {
                        console.error('Error loading suggestions:', error);
                    }
                },

                async refreshData() {
                    if (this.isRefreshing) return; // Prevenir múltiples clics
                    
                    this.isRefreshing = true;
                    try {
                        // Mostrar notificación de inicio
                        this.showNotification('Actualizando sugerencias...');
                        
                        // Cargar nuevas sugerencias aleatorias
                        const response = await fetch('/api/suggestions?refresh=true&random=' + Math.random());
                        const newSuggestions = await response.json();
                        
                        // Actualizar las sugerencias con las nuevas
                        this.suggestions = newSuggestions;
                        
                        // También recargar hábitos del usuario para actualizar el estado
                        await this.loadUserHabits();
                        
                        // Mostrar notificación de éxito
                        this.showNotification('¡Nuevas sugerencias cargadas!');
                        
                    } catch (error) {
                        console.error('Error refreshing data:', error);
                        this.showNotification('Error al actualizar las sugerencias. Inténtalo de nuevo.');
                    } finally {
                        this.isRefreshing = false;
                    }
                },

                async completeHabit(habit) {
                    try {
                        // Actualizar el estado local inmediatamente para feedback visual
                        const habitIndex = this.userHabits.findIndex(h => h.id === habit.id);
                        if (habitIndex !== -1) {
                            this.userHabits[habitIndex].today_completed = true;
                            this.userHabits[habitIndex].status = 'completed';
                        }
                        
                        // Actualizar también en activeHabits si existe
                        const activeIndex = this.activeHabits.findIndex(h => h.id === habit.id);
                        if (activeIndex !== -1) {
                            this.activeHabits[activeIndex].today_completed = true;
                            this.activeHabits[activeIndex].status = 'completed';
                        }

                        const response = await fetch(`/habits/${habit.id}/complete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            
                            // Actualizar el hábito específico con datos del servidor si están disponibles
                            if (data.habit) {
                                if (habitIndex !== -1) {
                                    this.userHabits[habitIndex] = { ...this.userHabits[habitIndex], ...data.habit };
                                }
                                if (activeIndex !== -1) {
                                    this.activeHabits[activeIndex] = { ...this.activeHabits[activeIndex], ...data.habit };
                                }
                            }
                            
                            // Actualizar stats del usuario si están en la respuesta
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            
                            // Verificar level-up y mostrar confetti
                            if (data.leveled_up) {
                                setTimeout(() => {
                                    this.launchConfetti();
                                    this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                                }, 500);
                            }
                            
                            // Reordenar hábitos para mover completados al final
                            this.reorganizeHabits();
                            
                        } else {
                            // Si falló, revertir el cambio local
                            if (habitIndex !== -1) {
                                this.userHabits[habitIndex].today_completed = false;
                                this.userHabits[habitIndex].status = 'active';
                            }
                            if (activeIndex !== -1) {
                                this.activeHabits[activeIndex].today_completed = false;
                                this.activeHabits[activeIndex].status = 'active';
                            }
                            this.showNotification(data.message);
                        }
                    } catch (error) {
                        console.error('Error completing habit:', error);
                        // Revertir cambios en caso de error
                        const habitIndex = this.userHabits.findIndex(h => h.id === habit.id);
                        if (habitIndex !== -1) {
                            this.userHabits[habitIndex].today_completed = false;
                            this.userHabits[habitIndex].status = 'active';
                        }
                        const activeIndex = this.activeHabits.findIndex(h => h.id === habit.id);
                        if (activeIndex !== -1) {
                            this.activeHabits[activeIndex].today_completed = false;
                            this.activeHabits[activeIndex].status = 'active';
                        }
                        this.showNotification('Error al completar el hábito. Inténtalo de nuevo.');
                    }
                },

                async adoptSuggestion(suggestion) {
                    this.isAdopting = true;
                    try {
                        const response = await fetch('/habits/create-from-suggestion', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                suggestion_id: suggestion.id,
                                frequency: 'diario',
                                duration_days: 30,
                                motivation: suggestion.benefits,
                                reward: 'Sentirme mejor conmigo mismo'
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            

                            // Actualizar stats del usuario si están en la respuesta
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            

                            // Verificar level-up y mostrar confetti
                            if (data.leveled_up) {
                                setTimeout(() => {
                                    this.launchConfetti();
                                    this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                                }, 500);
                            }
                            

                            // Recargar hábitos y sugerencias para actualizar la vista
                            await this.loadUserHabits();
                            await this.loadSuggestions(); // Recargar sugerencias sin duplicados
                        }
                    } catch (error) {
                        console.error('Error adopting suggestion:', error);
                        this.showNotification('Error al adoptar el hábito. Inténtalo de nuevo.');
                    } finally {
                        this.isAdopting = false;
                    }
                },

                // Funciones para el formulario de creación
                nextStep() {
                    // Validaciones por paso
                    if (this.createForm.step === 1) {
                        if (!this.createForm.name.trim()) {
                            this.showNotification('Por favor, ingresa el nombre del hábito');
                            return;
                        }
                    } else if (this.createForm.step === 3) {
                        if (!this.createForm.motivation.trim()) {
                            this.showNotification('Por favor, describe tu motivación');
                            return;
                        }
                    }
                    
                    if (this.createForm.step < 5) {
                        this.createForm.step++;
                    }
                },

                async submitCreateForm() {
                    try {
                        const response = await fetch('/habits', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                name: this.createForm.name,
                                description: this.createForm.description,
                                frequency: this.createForm.frequency,
                                categoria: this.createForm.category,
                                duration_days: this.createForm.duration_days,
                                motivation: this.createForm.motivation,
                                reward: this.createForm.reward,
                                start_date: this.createForm.start_date
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            this.showCreateModal = false;
                            this.resetCreateForm();
                            

                            // Actualizar stats del usuario si están en la respuesta
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            

                            // Verificar level-up y mostrar confetti
                            if (data.leveled_up) {
                                setTimeout(() => {
                                    this.launchConfetti();
                                    this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                                }, 500);
                            }
                            
                            await this.loadUserHabits(); // Recargar la lista de hábitos
                        } else {
                            this.showNotification(data.message || 'Error al crear el hábito');
                        }
                    } catch (error) {
                        console.error('Error creating habit:', error);
                        this.showNotification('Error al crear el hábito. Inténtalo de nuevo.');
                    }
                },

                resetCreateForm() {
                    this.createForm = {
                        step: 1,
                        name: '',
                        description: '',
                        frequency: 'diario',
                        category: 'bienestar',
                        duration_days: 30,
                        motivation: '',
                        reward: '',
                        start_date: new Date().toISOString().split('T')[0]
                    };
                },

                showNotification(message) {
                    this.notification.message = message;
                    this.notification.show = true;
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                },

                // Función para lanzar confeti cuando sube de nivel
                launchConfetti() {
                    // Confeti desde arriba
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 },
                        colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                    });

                    // Confeti lateral izquierdo
                    setTimeout(() => {
                        confetti({
                            particleCount: 50,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0, y: 0.8 },
                            colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                        });
                    }, 200);

                    // Confeti lateral derecho
                    setTimeout(() => {
                        confetti({
                            particleCount: 50,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1, y: 0.8 },
                            colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                        });
                    }, 400);

                    // Confeti final desde el centro
                    setTimeout(() => {
                        confetti({
                            particleCount: 150,
                            spread: 360,
                            startVelocity: 30,
                            decay: 0.9,
                            scalar: 1.2,
                            origin: { x: 0.5, y: 0.5 },
                            colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                        });
                    }, 600);
                },

                showCategoryDetails(category, habits) {
                    // Crear un modal dinámico para mostrar hábitos de la categoría
                    if (habits && habits.length > 0) {
                        const categoryName = category.charAt(0).toUpperCase() + category.slice(1);
                        const icon = this.getCategoryIcon(category);
                        
                        let habitList = habits.map(habit => 
                            `<div class="flex items-center justify-between p-3 bg-white/5 rounded-lg mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-xl">${habit.icon}</span>
                                    <div>
                                        <div class="text-white font-medium">${habit.name}</div>
                                        <div class="text-white/60 text-sm">${habit.description}</div>
                                    </div>
                                </div>
                                <button onclick="habitApp().adoptSuggestionById(${habit.id})" 
                                        class="bg-motiveo-primary hover:bg-motiveo-primary/80 px-3 py-1 rounded text-sm text-white">
                                    Adoptar
                                </button>
                            </div>`
                        ).join('');

                        this.showNotification(`${icon} ${categoryName}: ${habits.length} hábitos disponibles`);
                    }
                },

                async adoptSuggestionById(suggestionId) {
                    const suggestion = this.findSuggestionById(suggestionId);
                    if (suggestion) {
                        await this.adoptSuggestion(suggestion);
                    }
                },

                findSuggestionById(id) {
                    // Buscar en sugerencias populares
                    let suggestion = this.suggestions.popular.find(s => s.id === id);
                    if (suggestion) return suggestion;
                    
                    // Buscar en categorías
                    for (let category in this.suggestions.by_category) {
                        suggestion = this.suggestions.by_category[category].find(s => s.id === id);
                        if (suggestion) return suggestion;
                    }
                    return null;
                },

                getCategoryIcon(categoria) {
                    // Iconos animados Lottie directos
                    const icons = {
                        'salud': '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'productividad': '<lottie-player src="/animations/productivity.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'bienestar': '<lottie-player src="/animations/wellbeing.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'aprendizaje': '<lottie-player src="/animations/learning.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'finanzas': '<lottie-player src="/animations/finances.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'relaciones': '<lottie-player src="/animations/relationships.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'ejercicio': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'fitness': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'deporte': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                        'correr': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>'
                    };
                    return icons[categoria] || '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                },

                // Nueva función para detectar hábitos de ejercicio por nombre/descripción
                getHabitIcon(habit) {
                    const nombre = habit.nombre?.toLowerCase() || '';
                    const descripcion = habit.descripcion?.toLowerCase() || '';
                    const categoria = habit.categoria?.toLowerCase() || '';
                    
                    // Palabras clave relacionadas con ejercicio/correr
                    const ejercicioKeywords = ['correr', 'running', 'trotar', 'caminar', 'walk', 'ejercicio', 'gym', 'gimnasio', 'fitness', 'entrenamiento', 'cardio', 'deportes', 'deporte'];
                    const saludKeywords = ['salud', 'dormir', 'vitaminas', 'medicina', 'doctor', 'hospital', 'nutrición'];
                    const aguaKeywords = ['agua', 'hidrat', 'beber', 'líquido', 'water'];
                    const meditacionKeywords = ['meditar', 'meditation', 'mindfulness', 'relajar', 'yoga', 'respirar', 'calma', 'zen'];
                    const lecturaKeywords = ['leer', 'lectura', 'libro', 'estudiar', 'aprender', 'curso', 'educación'];
                    const finanzasKeywords = ['dinero', 'ahorro', 'presupuesto', 'invertir', 'finanzas', 'económico', 'gastos'];
                    const relacionesKeywords = ['familia', 'amigos', 'pareja', 'social', 'comunicar', 'amor', 'relación'];
                    const productividadKeywords = ['trabajo', 'productividad', 'planificar', 'organizar', 'metas', 'objetivos', 'tareas'];
                    
                    const esEjercicio = ejercicioKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esSalud = saludKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esAgua = aguaKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword)
                    );
                    
                    const esMeditacion = meditacionKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esLectura = lecturaKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esFinanzas = finanzasKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esRelaciones = relacionesKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    const esProductividad = productividadKeywords.some(keyword => 
                        nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
                    );
                    
                    if (esEjercicio) {
                        return '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esAgua) {
                        return '<lottie-player src="/animations/water.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esSalud) {
                        return '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esMeditacion) {
                        return '<lottie-player src="/animations/meditation.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esLectura) {
                        return '<lottie-player src="/animations/learning.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esFinanzas) {
                        return '<lottie-player src="/animations/finances.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esRelaciones) {
                        return '<lottie-player src="/animations/relationships.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    } else if (esProductividad) {
                        return '<lottie-player src="/animations/productivity.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
                    }
                    
                    // Si no encuentra coincidencias específicas, usa el icono de categoría
                    return this.getCategoryIcon(categoria);
                },

                // Nuevas funciones para el modal expandido
                expandHabit(habit) {
                    this.expandedHabit = habit;
                },

                async handleHabitAction(habitId, action) {
                    try {
                        let endpoint = '';
                        let method = 'POST';
                        
                        if (action === 'complete') {
                            endpoint = `/habits/${habitId}/complete`;
                        } else if (action === 'undo') {
                            endpoint = `/habits/${habitId}/undo`;
                        }

                        const response = await fetch(endpoint, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            
                            // Actualizar el hábito específico con los nuevos datos del backend
                            if (data.habit) {
                                // Encontrar y actualizar el hábito en la lista principal
                                const habitIndex = this.userHabits.findIndex(h => h.id === habitId);
                                if (habitIndex !== -1) {
                                    this.userHabits[habitIndex] = { ...this.userHabits[habitIndex], ...data.habit };
                                }
                                
                                // Actualizar también en activeHabits si existe
                                const activeIndex = this.activeHabits.findIndex(h => h.id === habitId);
                                if (activeIndex !== -1) {
                                    this.activeHabits[activeIndex] = { ...this.activeHabits[activeIndex], ...data.habit };
                                }
                                
                                // Actualizar también en completedHabits si existe
                                const completedIndex = this.completedHabits.findIndex(h => h.id === habitId);
                                if (completedIndex !== -1) {
                                    this.completedHabits[completedIndex] = { ...this.completedHabits[completedIndex], ...data.habit };
                                }
                                
                                // Actualizar el hábito expandido inmediatamente
                                if (this.expandedHabit && this.expandedHabit.id === habitId) {
                                    this.expandedHabit = { ...this.expandedHabit, ...data.habit };
                                }
                            }
                            
                            // Actualizar stats del usuario si están en la respuesta
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            
                            // Verificar level-up y mostrar confetti
                            if (data.leveled_up) {
                                setTimeout(() => {
                                    this.launchConfetti();
                                    this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                                }, 500);
                            }
                            
                            // Solo recargar hábitos si no tenemos datos específicos del hábito
                            if (!data.habit) {
                                await this.loadUserHabits();
                                
                                // Actualizar el hábito expandido con los nuevos datos
                                if (this.expandedHabit && this.expandedHabit.id === habitId) {
                                    const updatedHabit = this.userHabits.find(h => h.id === habitId);
                                    if (updatedHabit) {
                                        this.expandedHabit = updatedHabit;
                                    }
                                }
                            }
                        } else {
                            this.showNotification(data.message);
                        }
                    } catch (error) {
                        console.error('Error handling habit action:', error);
                        this.showNotification('Error al procesar la acción. Inténtalo de nuevo.');
                    }
                },

                getHabitSteps(habit) {
                    if (!habit) return [];
                    
                    // Generar pasos basados en la categoría del hábito
                    const stepsByCategory = {
                        'salud': [
                            'Prepara el espacio adecuado para la actividad',
                            'Comienza con una intensidad moderada',
                            'Mantén un ritmo constante durante la actividad',
                            'Escucha a tu cuerpo y ajusta según sea necesario',
                            'Registra tu progreso y cómo te sientes'
                        ],
                        'productividad': [
                            'Elimina las distracciones de tu entorno',
                            'Define objetivos claros para esta sesión',
                            'Organiza las tareas por prioridad',
                            'Trabaja con bloques de tiempo concentrado',
                            'Evalúa lo logrado y planifica el siguiente paso'
                        ],
                        'bienestar': [
                            'Encuentra un momento de tranquilidad',
                            'Respira profundamente y relájate',
                            'Concéntrate en el presente y tus sensaciones',
                            'Dedica tiempo completo a esta actividad',
                            'Reflexiona sobre los beneficios obtenidos'
                        ],
                        'aprendizaje': [
                            'Prepara los materiales necesarios',
                            'Revisa brevemente el contenido anterior',
                            'Enfócate en comprender conceptos clave',
                            'Practica lo aprendido con ejemplos',
                            'Toma notas y resume los puntos importantes'
                        ]
                    };

                    // Pasos genéricos si la categoría no está definida
                    const genericSteps = [
                        'Prepárate mental y físicamente para la actividad',
                        'Comienza con enfoque y determinación',
                        'Mantén la constancia durante todo el proceso',
                        'Supera cualquier resistencia que puedas sentir',
                        'Celebra haber completado este hábito positivo'
                    ];

                    return stepsByCategory[habit.category] || stepsByCategory[habit.categoria] || genericSteps;
                },

                showEditHabit(habit) {
                    // Cerrar el modal expandido usando la función apropiada
                    this.closeHabitDetails();
                    
                    // Llenar el formulario de edición con los datos del hábito
                    this.editForm = {
                        id: habit.id,
                        nombre: habit.nombre,
                        categoria: habit.categoria,
                        duration_days: habit.duration_days,
                        motivation: habit.motivation || '',
                        reward: habit.reward || ''
                    };
                    
                    // Mostrar el modal de edición
                    this.showEditModal = true;
                },

                async updateHabit() {
                    try {
                        const response = await fetch(`/habits/${this.editForm.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                nombre: this.editForm.nombre,
                                categoria: this.editForm.categoria,
                                duration_days: this.editForm.duration_days,
                                motivation: this.editForm.motivation,
                                reward: this.editForm.reward
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showNotification('Hábito actualizado exitosamente');
                            this.showEditModal = false;
                            await this.loadUserHabits(); // Recargar hábitos
                        } else {
                            this.showNotification('Error al actualizar el hábito');
                        }
                    } catch (error) {
                        console.error('Error updating habit:', error);
                        this.showNotification('Error al actualizar el hábito');
                    }
                },

                confirmDeleteHabit(habit) {
                    if (confirm(`¿Estás seguro de que quieres eliminar el hábito "${habit.nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                        this.deleteHabit(habit.id);
                    }
                },

                async deleteHabit(habitId) {
                    try {
                        const response = await fetch(`/habits/${habitId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showNotification('Hábito eliminado exitosamente');
                            this.closeHabitDetails(); // Cerrar modal expandido si está abierto
                            await this.loadUserHabits(); // Recargar hábitos
                        } else {
                            this.showNotification('Error al eliminar el hábito');
                        }
                    } catch (error) {
                        console.error('Error deleting habit:', error);
                        this.showNotification('Error al eliminar el hábito');
                    }
                },

                // Habit Explorer Methods
                async openHabitExplorer() {
                    console.log('Opening habit explorer...');
                    this.showHabitExplorer = true;
                    await this.loadAllHabits();
                },

                async loadAllHabits() {
                    console.log('Loading all habits...');
                    this.explorerLoading = true;
                    try {
                        const params = new URLSearchParams({
                            search: this.explorerFilters.search,
                            category: this.explorerFilters.category,
                            sort: this.explorerFilters.sort
                        });

                        console.log('Fetching:', `/habits/suggestions?${params}`);
                        const response = await fetch(`/habits/suggestions?${params}`);
                        const data = await response.json();
                        
                        console.log('Response data:', data);
                        this.explorerHabits = data.suggestions || [];
                        console.log('Explorer habits loaded:', this.explorerHabits.length);
                    } catch (error) {
                        console.error('Error loading all habits:', error);
                        this.showNotification('Error al cargar los hábitos');
                    } finally {
                        this.explorerLoading = false;
                    }
                },

                async searchHabits() {
                    await this.loadAllHabits();
                },

                async adoptSuggestionFromExplorer(habit) {
                    try {
                        console.log('[ADOPT] Adoptando hábito:', habit);
                        this.isAdopting = true;
                        
                        const response = await fetch(`/habits/suggestions/${habit.id}/add`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        console.log('[RESPONSE] Respuesta del servidor:', data);
                        
                        if (data.success) {
                            this.showNotification(`${habit.name} agregado a Mis Hábitos!`);
                            
                            // Celebration
                            this.launchConfetti();
                            
                            // Pequeño delay para asegurar que la DB se actualice
                            await new Promise(resolve => setTimeout(resolve, 500));
                            
                            // Update UI - con logs de depuración
                            console.log('[RELOAD] Recargando hábitos del usuario...');
                            await this.loadUserHabits();
                            console.log('[SUCCESS] Hábitos del usuario recargados. Total:', this.userHabits.length);
                            
                            await this.loadSuggestions();
                            
                            // Optionally close explorer after adoption
                            // this.showHabitExplorer = false;
                        } else {
                            console.error('[ERROR] Error en la respuesta:', data);
                            this.showNotification(data.message || 'Error al agregar el hábito');
                        }
                    } catch (error) {
                        console.error('Error adopting habit from explorer:', error);
                        this.showNotification('Error al agregar el hábito');
                    } finally {
                        this.isAdopting = false;
                    }
                },

                showHabitDetails(habit) {
                    // Recordar que venimos del explorador
                    this.fromExplorer = this.showHabitExplorer;
                    
                    // Cerrar el explorador de hábitos
                    this.showHabitExplorer = false;
                    
                    // Show detailed view of habit in a modal or expanded view
                    this.expandedHabit = {
                        ...habit,
                        is_completed: false,
                        current_streak: 0,
                        best_streak: 0,
                        type: 'suggested'
                    };
                },

                closeHabitDetails() {
                    this.expandedHabit = null;
                    
                    // Si veníamos del explorador, reabrirlo
                    if (this.fromExplorer) {
                        this.showHabitExplorer = true;
                        this.fromExplorer = false; // Reset para futuras aperturas
                    }
                },

                async debugHabits() {
                    try {
                        console.log('[DEBUG] Depurando hábitos...');
                        const response = await fetch('/debug/habits');
                        const data = await response.json();
                        console.log('[DEBUG] Datos de depuración:', data);
                        this.showNotification(`Debug: ${data.total_habits} hábitos encontrados`);
                    } catch (error) {
                        console.error('[ERROR] Error en debug:', error);
                        this.showNotification('Error en debug');
                    }
                },

                getCategoryStyle(categoria) {
                    const styles = {
                        'salud': 'bg-red-500/20',
                        'productividad': 'bg-blue-500/20',
                        'bienestar': 'bg-purple-500/20',
                        'aprendizaje': 'bg-yellow-500/20',
                        'finanzas': 'bg-green-500/20',
                        'relaciones': 'bg-pink-500/20'
                    };
                    return styles[categoria] || 'bg-gray-500/20';
                }
            }
        }
    </script>

    <!-- Custom CSS Animations -->
    <style>
        /* Animaciones de entrada y carga */
        body.loaded {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* Animaciones de deslizamiento */
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeInLeft {
            from { transform: translateX(-30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeInRight {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Animaciones de deslizamiento para el explorador de hábitos */
        @keyframes slideInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animaciones de elementos específicos */
        @keyframes bounceSubtle {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-3px); }
            60% { transform: translateY(-2px); }
        }

        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-3deg); }
            75% { transform: rotate(3deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
            50% { box-shadow: 0 0 30px rgba(139, 92, 246, 0.6); }
        }

        @keyframes textGlow {
            0%, 100% { text-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
            50% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.6); }
        }

        @keyframes textShine {
            0% { background-position: -100%; }
            100% { background-position: 100%; }
        }

        @keyframes progressFill {
            from { width: 0%; }
            to { width: var(--progress-width, 0%); }
        }

        @keyframes numberCount {
            from { transform: scale(0.8); opacity: 0.5; }
            to { transform: scale(1); opacity: 1; }
        }

        @keyframes cardAppear {
            from { 
                transform: translateY(30px) scale(0.95); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0) scale(1); 
                opacity: 1; 
            }
        }

        @keyframes completedGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3); }
            50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.6); }
        }

        @keyframes iconBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes fireFlicker {
            0%, 100% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.1) rotate(-2deg); }
            75% { transform: scale(1.05) rotate(2deg); }
        }

        @keyframes starTwinkle {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 1; }
            50% { transform: scale(1.2) rotate(180deg); opacity: 0.8; }
        }

        @keyframes calendarFlip {
            0%, 100% { transform: rotateY(0deg); }
            50% { transform: rotateY(180deg); }
        }

        @keyframes statusPulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        @keyframes successPulse {
            0%, 100% { background-color: rgb(34, 197, 94); transform: scale(1); }
            50% { background-color: rgb(22, 163, 74); transform: scale(1.1); }
        }

        @keyframes warningPulse {
            0%, 100% { background-color: rgb(251, 191, 36); transform: scale(1); }
            50% { background-color: rgb(245, 158, 11); transform: scale(1.1); }
        }

        @keyframes textShimmer {
            0% { opacity: 0.7; }
            50% { opacity: 1; }
            100% { opacity: 0.7; }
        }

        @keyframes pulseButton {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes textTyping {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes spinReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes bugWiggle {
            0%, 100% { transform: rotate(0deg); }
            10% { transform: rotate(-5deg); }
            20% { transform: rotate(5deg); }
            30% { transform: rotate(-3deg); }
            40% { transform: rotate(3deg); }
            50% { transform: rotate(0deg); }
        }

        /* Aplicación de animaciones */
        .animate-slide-down { animation: slideDown 0.8s ease-out; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        .animate-fade-in-left { animation: fadeInLeft 0.8s ease-out; }
        .animate-fade-in-right { animation: fadeInRight 0.8s ease-out; }
        .animate-fade-in-right-delayed { animation: fadeInRight 0.8s ease-out 0.2s both; }
        .animate-fade-in-delayed { animation: fadeInLeft 0.6s ease-out 0.3s both; }
        
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out; }
        .animate-text-typing { animation: textTyping 0.3s ease-out; }
        .animate-spin-reverse { animation: spinReverse 0.5s linear; }
        
        .animate-bounce-subtle { animation: bounceSubtle 2s infinite; }
        .animate-bounce-gentle { animation: bounceGentle 2s infinite; }
        .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-float-delayed { animation: float 3s ease-in-out infinite; animation-delay: 1s; }
        
        .animate-pulse-glow { animation: pulseGlow 2s infinite; }
        .animate-text-glow { animation: textGlow 3s infinite; }
        .animate-text-shine { 
            background: linear-gradient(90deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,1) 50%, rgba(255,255,255,0.8) 100%);
            background-size: 200% 100%;
            animation: textShine 3s infinite;
            -webkit-background-clip: text;
            background-clip: text;
        }
        
        .animate-progress-fill { animation: progressFill 1.5s ease-out; }
        .animate-number-count { animation: numberCount 0.3s ease-out; }
        .animate-card-appear { animation: cardAppear 0.7s ease-out; }
        .animate-completed-glow { animation: completedGlow 2s infinite; }
        
        .animate-icon-bounce { animation: iconBounce 2s infinite; }
        .animate-fire-flicker { animation: fireFlicker 2s infinite; }
        .animate-star-twinkle { animation: starTwinkle 3s infinite; }
        .animate-calendar-flip { animation: calendarFlip 2s infinite; }
        .animate-status-pulse { animation: statusPulse 2s infinite; }
        .animate-success-pulse { animation: successPulse 2s infinite; }
        .animate-warning-pulse { animation: warningPulse 2s infinite; }
        .animate-text-shimmer { animation: textShimmer 2s infinite; }
        .animate-pulse-button { animation: pulseButton 2s infinite; }
        .animate-bug-wiggle:hover { animation: bugWiggle 1s ease-in-out; }

        /* Animaciones de hover */
        .animate-wiggle-on-hover:hover { animation: wiggle 0.5s ease-in-out; }
        .animate-spin-on-hover:hover .fas { animation: spin 0.5s linear; }

        /* Efectos de hover para grupos */
        .group:hover .group-button-hover\:animate-spin-reverse {
            animation: spin 0.5s linear reverse;
        }

        /* Responsive animations */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Mejoras visuales adicionales */
        .backdrop-blur-enhanced {
            backdrop-filter: blur(12px) saturate(200%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        // Agregar efectos de animación dinámicos
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de carga inicial
            setTimeout(() => {
                document.body.classList.add('loaded');
            }, 100);

            // Efectos de partículas en hover para botones importantes
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('mouseenter', function(e) {
                    if (this.classList.contains('animate-pulse-button')) {
                        this.style.boxShadow = '0 0 30px rgba(139, 92, 246, 0.6)';
                        this.style.transform = 'scale(1.05) translateY(-2px)';
                    }
                });

                button.addEventListener('mouseleave', function(e) {
                    this.style.boxShadow = '';
                    this.style.transform = '';
                });

                // Efecto de onda al hacer clic
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                        z-index: 1;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        if (ripple.parentNode) {
                            ripple.parentNode.removeChild(ripple);
                        }
                    }, 600);
                });
            });

            // Animación de números contadores
            function animateCounter(element, target, duration = 1000) {
                const start = parseInt(element.textContent) || 0;
                const increment = (target - start) / (duration / 16);
                let current = start;
                
                const timer = setInterval(() => {
                    current += increment;
                    if ((increment > 0 && current >= target) || (increment < 0 && current <= target)) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.round(current);
                }, 16);
            }

            // Observador para animaciones en scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'cardAppear 0.6s ease-out forwards';
                        
                        // Animar contadores si los encuentra
                        const counters = entry.target.querySelectorAll('[data-counter]');
                        counters.forEach(counter => {
                            const target = parseInt(counter.getAttribute('data-counter'));
                            animateCounter(counter, target);
                        });
                        
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Efectos de paralaje suave en scroll
            let ticking = false;
            function updateParallax() {
                const scrolled = window.pageYOffset;
                const parallaxElements = document.querySelectorAll('[data-parallax]');
                
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-parallax') || 0.5;
                    const transform = `translateY(${scrolled * speed}px)`;
                    element.style.transform = transform;
                });
                
                ticking = false;
            }

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            });

            // Efecto de cursor personalizado para áreas interactivas
            const cursor = document.createElement('div');
            cursor.className = 'custom-cursor';
            cursor.style.cssText = `
                position: fixed;
                width: 20px;
                height: 20px;
                background: radial-gradient(circle, rgba(139, 92, 246, 0.8) 0%, rgba(139, 92, 246, 0) 70%);
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                mix-blend-mode: difference;
                transition: transform 0.1s ease;
                display: none;
            `;
            document.body.appendChild(cursor);

            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';
                cursor.style.display = 'block';
            });

            document.addEventListener('mouseleave', () => {
                cursor.style.display = 'none';
            });

            // Mejorar hover effects para tarjetas
            document.querySelectorAll('.habit-card, .suggestion-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                    this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });
        });

        // Agregar animación de ripple en CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                0% { transform: scale(0); opacity: 1; }
                100% { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
