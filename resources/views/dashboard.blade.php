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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canvas Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Panel - Mis Hábitos y Toggle para Sugerencias -->
            <div class="space-y-6">
                <!-- Panel de Mis Hábitos (siempre visible si hay hábitos) -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20" x-show="userHabits.length > 0">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-motiveo-warning rounded-full flex items-center justify-center">
                                <span class="text-white text-lg">🏆</span>
                            </div>
                            <h2 class="text-xl font-bold text-white">Mis Hábitos</h2>
                            <span class="bg-motiveo-success/20 text-motiveo-success px-2 py-1 rounded-full text-xs font-bold" x-text="`${userHabits.length}`"></span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="habit in userHabits" :key="habit.id">
                            <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                             :class="getCategoryStyle(habit.categoria)">
                                            <span class="text-lg" x-text="getCategoryIcon(habit.categoria)"></span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-white font-semibold" x-text="habit.nombre"></h3>
                                            <div class="flex items-center space-x-2 text-sm">
                                                <span class="text-motiveo-success" x-text="`🔥 ${habit.dias_racha} días`"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="completeHabit(habit)"
                                            :disabled="habit.is_completed"
                                            :class="habit.is_completed ? 'bg-gray-500' : 'bg-motiveo-success hover:bg-motiveo-success/80'"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all">
                                        <span x-text="habit.is_completed ? '✅ Hecho' : 'Completar'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button @click="showCreateModal = true" 
                            class="w-full mt-6 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white py-3 px-4 rounded-xl font-semibold hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center">
                        Crear Nuevo Habito
                    </button>
                </div>

                <!-- Panel de Sugerencias (siempre visible) -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-motiveo-accent rounded-full flex items-center justify-center">
                                <span class="text-white text-lg">💡</span>
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
                        <h2 class="text-xl font-bold text-gray-900">Crear Nuevo Hábito</h2>
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
