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
    
    <style>
        /* Estilos para las tarjetas de hábitos */
        .habit-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
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
        
        /* Grid responsivo */
        .habits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .habits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-motiveo-dark via-purple-900 to-indigo-900 font-display" x-data="habitApp()">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-lg font-black text-white">M</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">MOTIVEO</h1>
                </div>

                <!-- User Level & XP -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-3">
                        <div class="bg-motiveo-warning text-white px-3 py-1 rounded-full text-sm font-bold" x-text="`NIVEL ${userStats.level}`">
                            NIVEL {{ auth()->user()->level ?? 1 }}
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full transition-all duration-500" 
                                     :style="`width: ${userStats.progress}%`"
                                     style="width: {{ auth()->user()->getLevelProgress() ?? 0 }}%"></div>
                            </div>
                            <span class="text-white text-sm" x-text="`${userStats.xp}/${userStats.next_level_xp} XP`">
                                {{ auth()->user()->xp ?? 0 }}/{{ auth()->user()->getXpForNextLevel() ?? 100 }} XP
                            </span>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-motiveo-pink to-red-400 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white/80 hover:text-white text-sm">Salir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 bg-motiveo-success text-white px-6 py-3 rounded-lg shadow-lg z-50"
         x-text="notification.message">
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Título y Filtros -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-white mb-2">Mis Hábitos</h2>
                <p class="text-white/60">Gestiona tus hábitos diarios de forma organizada</p>
            </div>
            <div class="flex space-x-3">
                <button @click="showCreateModal = true" 
                        class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                    <i class="fas fa-plus mr-2"></i>Nuevo Hábito
                </button>
                <button @click="loadUserHabits()" 
                        class="bg-white/10 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-white/20 transition-all">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <!-- Grid de Hábitos -->
        <div class="habits-grid mb-12" x-show="userHabits.length > 0">
            <template x-for="habit in userHabits" :key="habit.id">
                <div class="habit-card bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 cursor-pointer hover:border-motiveo-primary/50 transition-all duration-300"
                     :class="habit.is_completed ? 'habit-completed' : 'habit-pending'"
                     @click="expandHabit(habit)"
                     title="Haz clic para ver detalles del hábito">
                    
                    <!-- Header de la tarjeta -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                 :class="getCategoryStyle(habit.categoria)">
                                <span class="text-xl" x-text="getCategoryIcon(habit.categoria)"></span>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg" x-text="habit.nombre"></h3>
                                <p class="text-white/60 text-sm capitalize" x-text="habit.categoria"></p>
                            </div>
                        </div>
                        
                        <!-- Estado visual -->
                        <div class="flex flex-col items-end">
                            <div class="w-3 h-3 rounded-full mb-2"
                                 :class="habit.is_completed ? 'bg-motiveo-success' : 'bg-motiveo-warning'"></div>
                            <span class="text-xs text-white/60" 
                                  x-text="habit.is_completed ? 'Completado' : 'Pendiente'"></span>
                        </div>
                    </div>

                    <!-- Descripción breve -->
                    <p class="text-white/80 text-sm mb-4 line-clamp-2" x-text="habit.descripcion || 'Descripción del hábito'"></p>

                    <!-- Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 text-sm">
                            <span class="text-motiveo-success flex items-center">
                                <i class="fas fa-fire mr-1"></i>
                                <span x-text="`${habit.dias_racha} días`"></span>
                            </span>
                            <span class="text-motiveo-accent flex items-center">
                                <i class="fas fa-star mr-1"></i>
                                <span x-text="`${habit.xp_ganada || 0} XP`"></span>
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-white/60">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>Día <span x-text="habit.dias_activo || 1"></span></span>
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <button @click.stop="habit.is_completed ? handleHabitAction(habit.id, 'undo') : handleHabitAction(habit.id, 'complete')"
                            :disabled="false"
                            :class="habit.is_completed 
                                ? 'bg-motiveo-success/20 text-motiveo-success border-motiveo-success/30 hover:bg-motiveo-warning/20 hover:text-motiveo-warning hover:border-motiveo-warning' 
                                : 'bg-motiveo-warning/20 text-motiveo-warning border-motiveo-warning hover:bg-motiveo-warning hover:text-white'"
                            class="w-full py-3 px-4 rounded-xl font-semibold transition-all border-2 flex items-center justify-center">
                        <template x-if="habit.is_completed">
                            <div class="flex items-center">
                                <i class="fas fa-undo mr-2"></i>
                                Deshacer Completado
                            </div>
                        </template>
                        <template x-if="!habit.is_completed">
                            <div class="flex items-center">
                                <i class="fas fa-play mr-2"></i>
                                Iniciar Hábito
                            </div>
                        </template>
                    </button>
                </div>
            </template>
        </div>

        <!-- Estado vacío -->
        <div x-show="userHabits.length === 0" class="text-center py-16">
            <div class="text-6xl mb-6">🎯</div>
            <h3 class="text-2xl font-bold text-white mb-4">¡Comienza tu viaje!</h3>
            <p class="text-white/60 mb-8 max-w-lg mx-auto">
                Aún no tienes hábitos creados. Comienza creando tu primer hábito y da el primer paso hacia una mejor versión de ti mismo.
            </p>
            <button @click="showCreateModal = true" 
                    class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-rocket mr-2"></i>Crear Mi Primer Hábito
            </button>
        </div>

        <!-- Sección de Hábitos Sugeridos -->
        <div class="mt-16">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-2">Hábitos Sugeridos</h3>
                    <p class="text-white/60">Descubre nuevos hábitos que podrían interesarte</p>
                </div>
                <button @click="loadSuggestions()" 
                        class="bg-white/10 backdrop-blur-md text-white px-4 py-3 rounded-xl hover:bg-white/20 transition-all">
                    <i class="fas fa-refresh mr-2"></i>Actualizar
                </button>
            </div>

            <!-- Grid de Sugerencias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-show="suggestions.popular && suggestions.popular.length > 0">
                <template x-for="suggestion in suggestions.popular" :key="suggestion.id">
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all cursor-pointer"
                         @click="adoptSuggestion(suggestion)">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                 :class="getCategoryStyle(suggestion.categoria)">
                                <span class="text-lg" x-text="getCategoryIcon(suggestion.categoria)"></span>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold text-sm" x-text="suggestion.name"></h4>
                                <p class="text-white/60 text-xs capitalize" x-text="suggestion.categoria"></p>
                            </div>
                        </div>
                        <p class="text-white/70 text-xs mb-3 line-clamp-2" x-text="suggestion.description"></p>
                        <button class="w-full bg-motiveo-accent/20 text-motiveo-accent py-2 px-3 rounded-lg text-xs font-medium hover:bg-motiveo-accent hover:text-white transition-all">
                            <i class="fas fa-plus mr-1"></i>Agregar
                        </button>
                    </div>
                </template>
            </div>

            <!-- Categorías de sugerencias -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-white mb-4">Explorar por Categoría</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="[category, habits] in Object.entries(suggestions.by_category || {})" :key="category">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all cursor-pointer"
                             @click="showCategoryDetails(category, habits)">
                            <div class="text-center">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-2"
                                     :class="getCategoryStyle(category)">
                                    <span class="text-xl" x-text="getCategoryIcon(category)"></span>
                                </div>
                                <h5 class="text-white font-medium text-sm capitalize" x-text="category"></h5>
                                <p class="text-white/60 text-xs" x-text="`${habits.length} hábitos`"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
                            </div>
                            <h2 class="text-xl font-bold text-white">
                                <span x-show="userHabits.length === 0">¡Comienza tu Viaje!</span>
                                <span x-show="userHabits.length > 0">Más Hábitos Populares</span>
                            </h2>
                        </div>
                        <button @click="loadSuggestions()" 
                                class="text-motiveo-accent hover:text-motiveo-accent/80 text-sm font-medium">
                            🔄 Renovar
                        </button>
                    </div>

                    <div x-show="userHabits.length === 0" class="mb-4 p-4 bg-motiveo-primary/10 rounded-xl border border-motiveo-primary/20">
                        <div class="flex items-center space-x-2 text-motiveo-accent mb-2">
                            <span class="text-lg">🌟</span>
                            <span class="font-semibold">¡Bienvenido a Motiveo!</span>
                        </div>
                        <p class="text-white/80 text-sm">
                            Selecciona algunos hábitos populares para comenzar tu transformación personal.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <template x-for="suggestion in suggestions.popular" :key="suggestion.id">
                            <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all border border-white/5 hover:border-white/20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="text-2xl" x-text="suggestion.icon"></div>
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
                                        <span x-show="!isAdopting">❤️</span>
                                        <span x-show="isAdopting">⏳</span>
                                        <span x-text="isAdopting ? 'Agregando...' : 'Me gusta'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Mensaje cuando no hay sugerencias -->
                        <div x-show="suggestions.popular && suggestions.popular.length === 0" 
                             class="text-center py-8">
                            <div class="text-6xl mb-4">🎉</div>
                            <h3 class="text-white font-semibold mb-2">¡Excelente progreso!</h3>
                            <p class="text-white/60 text-sm">
                                Ya tienes muchos hábitos populares. Explora las categorías below o crea un hábito personalizado.
                            </p>
                        </div>
                    </div>

                    <!-- Categorías de sugerencias -->
                    <div class="mt-6 pt-6 border-t border-white/20">
                        <h3 class="text-white font-semibold mb-4 flex items-center">
                            <span class="mr-2">🏷️</span>
                            Por Categorías
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(habits, category) in suggestions.by_category" :key="category">
                                <div class="bg-white/5 rounded-lg p-3 hover:bg-white/10 transition-all cursor-pointer"
                                     @click="showCategoryDetails(category, habits)">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg" x-text="getCategoryIcon(category)"></span>
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
                            <span>🎨</span>
                            <span>¿No encuentras lo que buscas? Crea tu hábito personalizado</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Center Panel - Hábitos Activos -->
            <div class="space-y-6">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">🎯</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Hábitos de Hoy</h2>
                    </div>

                    <div class="space-y-4" x-show="activeHabits.length > 0">
                        <template x-for="habit in activeHabits" :key="habit.id">
                            <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl" x-text="getCategoryIcon(habit.categoria)"></span>
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
                                <div class="mb-3">
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
                                        <span class="text-motiveo-success" x-text="`🔥 ${habit.dias_racha}`"></span>
                                        <span class="text-white/60">días</span>
                                    </div>
                                    <button @click="completeHabit(habit)"
                                            :disabled="habit.completed_today || !habit.can_complete"
                                            :class="(habit.completed_today || !habit.can_complete) ? 'bg-gray-500' : 'bg-motiveo-success hover:bg-motiveo-success/80'"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all">
                                        <span x-text="habit.completed_today ? 'Completado' : 'Completar (+20 XP)'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="activeHabits.length === 0" class="text-center py-8">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-white font-semibold mb-2">¡Todos los hábitos completados!</h3>
                        <p class="text-white/60 text-sm">Excelente trabajo. ¡Sigue así!</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Completados y Estadísticas -->
            <div class="space-y-6">
                <!-- Completados Hoy -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">✅</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Completados Hoy</h2>
                    </div>

                    <div class="space-y-3" x-show="completedHabits.length > 0">
                        <template x-for="habit in completedHabits" :key="habit.id">
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <span x-text="getCategoryIcon(habit.categoria)"></span>
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
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">📊</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Estadísticas</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="text-center p-4 bg-white/5 rounded-lg">
                            <div class="text-3xl font-black text-motiveo-warning mb-1" x-text="userStats.xp">
                                {{ auth()->user()->xp ?? 0 }}
                            </div>
                            <div class="text-white/60 text-sm">Puntos de Experiencia</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-white/5 rounded-lg">
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
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="showCreateModal = false"
             class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center">
                        <span class="text-white text-lg">✨</span>
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
            <div class="px-6 pt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary h-2 rounded-full transition-all duration-300"
                         :style="`width: ${(createForm.step / 5) * 100}%`"></div>
                </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submitCreateForm()" class="p-6">
                
                <!-- Paso 1: Información Básica -->
                <div x-show="createForm.step === 1" class="space-y-6">
                    <div class="text-center mb-6">
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
                                        <div class="text-2xl mb-2">📅</div>
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
                                        <div class="text-2xl mb-2">📊</div>
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
                                        <div class="text-2xl mb-2">🏥</div>
                                        <div class="font-semibold">Salud</div>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="createForm.category" value="productividad" class="sr-only">
                                <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                     :class="createForm.category === 'productividad' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="text-center">
                                        <div class="text-2xl mb-2">🏢</div>
                                        <div class="font-semibold">Productividad</div>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="createForm.category" value="bienestar" class="sr-only">
                                <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                     :class="createForm.category === 'bienestar' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="text-center">
                                        <div class="text-2xl mb-2">😊</div>
                                        <div class="font-semibold">Bienestar</div>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="createForm.category" value="aprendizaje" class="sr-only">
                                <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                     :class="createForm.category === 'aprendizaje' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="text-center">
                                        <div class="text-2xl mb-2">📚</div>
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
                                        <div class="text-2xl mb-2">⏳</div>
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
                                        <div class="text-2xl mb-2">📅</div>
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
                                        <div class="text-2xl mb-2">🎯</div>
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
                                        <div class="text-2xl mb-2">🏆</div>
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
                            🎉 Crear Hábito
                        </button>
                    </div>
                </div>
            </form>
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
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.away="showEditModal = false">
            
            <form @submit.prevent="updateHabit()" class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">✏️ Editar Hábito</h2>
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
                            <option value="salud">🏃‍♂️ Salud</option>
                            <option value="productividad">💼 Productividad</option>
                            <option value="bienestar">🧘‍♀️ Bienestar</option>
                            <option value="aprendizaje">📚 Aprendizaje</option>
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
                        💾 Actualizar Hábito
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
         @click.self="expandedHabit = null">
        
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
                            <span x-text="expandedHabit?.icon || '🎯'" class="text-2xl"></span>
                        </div>
                        <div>
                            <h3 x-text="expandedHabit?.name" class="text-xl font-bold text-white"></h3>
                            <p x-text="expandedHabit?.category" class="text-motiveo-primary capitalize"></p>
                        </div>
                    </div>
                    <button @click="expandedHabit = null" 
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
                    <h4 class="text-lg font-semibold text-white mb-2">📝 Descripción</h4>
                    <p x-text="expandedHabit?.description || 'Sin descripción disponible'" 
                       class="text-gray-300 leading-relaxed"></p>
                </div>

                <!-- Progreso visual -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-3">📊 Progreso</h4>
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
                <div x-show="expandedHabit && !expandedHabit.is_completed">
                    <h4 class="text-lg font-semibold text-white mb-3">🎯 Guía paso a paso</h4>
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
                            ✅ Marcar como Completado
                        </button>
                    </template>
                    
                    <template x-if="expandedHabit && expandedHabit.is_completed">
                        <button @click="handleHabitAction(expandedHabit.id, 'undo')"
                                class="w-full py-3 px-4 bg-gradient-to-r from-motiveo-warning to-orange-500 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                            ↩️ Deshacer Completado
                        </button>
                    </template>
                    
                    <button @click="showEditHabit(expandedHabit)"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                        ✏️ Editar Hábito
                    </button>
                    
                    <button @click="confirmDeleteHabit(expandedHabit)"
                            class="w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                        🗑️ Eliminar Hábito
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
                        const response = await fetch('/api/user-habits');
                        const data = await response.json();
                        
                        this.activeHabits = data.active_habits || [];
                        this.completedHabits = data.completed_today || [];
                        this.userHabits = [...this.activeHabits, ...this.completedHabits];
                        this.totalHabits = this.userHabits.length;
                        this.userStats = data.user_stats;
                        
                        // Auto-mostrar sugerencias si no hay hábitos
                        if (this.userHabits.length === 0) {
                            this.showSuggestions = true;
                        }
                    } catch (error) {
                        console.error('Error loading habits:', error);
                    }
                },

                async loadSuggestions() {
                    try {
                        const response = await fetch('/api/suggestions');
                        this.suggestions = await response.json();
                    } catch (error) {
                        console.error('Error loading suggestions:', error);
                    }
                },

                async completeHabit(habit) {
                    try {
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
                            
                            this.loadUserHabits(); // Recargar hábitos
                        } else {
                            this.showNotification(data.message);
                        }
                    } catch (error) {
                        console.error('Error completing habit:', error);
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
                    const icons = {
                        'salud': '🏥',
                        'productividad': '🏢', 
                        'bienestar': '😊',
                        'aprendizaje': '📚'
                    };
                    return icons[categoria] || '🎯';
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
                    // Cerrar el modal expandido
                    this.expandedHabit = null;
                    
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
                            this.showNotification('✅ Hábito actualizado exitosamente');
                            this.showEditModal = false;
                            await this.loadUserHabits(); // Recargar hábitos
                        } else {
                            this.showNotification('❌ Error al actualizar el hábito');
                        }
                    } catch (error) {
                        console.error('Error updating habit:', error);
                        this.showNotification('❌ Error al actualizar el hábito');
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
                            this.showNotification('🗑️ Hábito eliminado exitosamente');
                            this.expandedHabit = null; // Cerrar modal expandido si está abierto
                            await this.loadUserHabits(); // Recargar hábitos
                        } else {
                            this.showNotification('❌ Error al eliminar el hábito');
                        }
                    } catch (error) {
                        console.error('Error deleting habit:', error);
                        this.showNotification('❌ Error al eliminar el hábito');
                    }
                },

                getCategoryStyle(categoria) {
                    const styles = {
                        'salud': 'bg-red-500/20',
                        'productividad': 'bg-blue-500/20',
                        'bienestar': 'bg-purple-500/20',
                        'aprendizaje': 'bg-yellow-500/20'
                    };
                    return styles[categoria] || 'bg-gray-500/20';
                }
            }
        }
    </script>
</body>
</html>
