<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motiveo - Seguimiento de Hábitos</title>
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
                        'warning': '#f59e0b',
                        'danger': '#ef4444',
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
<body class="h-full bg-gray-50 font-sans" x-data="habitApp()">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-sm font-bold text-white">M</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">Motiveo</h1>
                </div>

                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="bg-primary text-white px-2 py-1 rounded text-xs font-medium" x-text="`Nivel ${userStats.level}`">
                            Nivel {{ auth()->user()->level ?? 1 }}
                        </span>
                        <span class="text-gray-600" x-text="`${userStats.xp} XP`">
                            {{ auth()->user()->xp ?? 0 }} XP
                        </span>
                    </div>
                    
                    <!-- XP Progress Bar -->
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-accent h-2 rounded-full transition-all duration-500"
                             :style="`width: ${userStats.progress}%`"
                             style="width: {{ auth()->user()->getLevelProgress() ?? 0 }}%"></div>
                    </div>

                    <!-- User Menu -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 text-sm">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Motivation Message Module -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="text-center">
                <h2 class="text-lg font-medium text-gray-900 mb-2" x-text="motivationMessage.title">
                    Bienvenido de vuelta
                </h2>
                <p class="text-gray-600" x-text="motivationMessage.content">
                    Mantente enfocado en tus objetivos de hoy.
                </p>
            </div>
        </div>

        <!-- Active Habits Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Hábitos Activos</h3>
                <div class="flex space-x-3">
                    <a href="/quiz" 
                       class="bg-warning text-white px-4 py-2 rounded-lg hover:bg-yellow-600 font-medium transition-colors">
                        Quiz de Refuerzo
                    </a>
                    <button @click="showCreateModal = true" 
                            class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-medium transition-colors">
                        Crear Nuevo Hábito
                    </button>
                </div>
            </div>

            <!-- Habits Grid -->
            <div x-show="activeHabits.length > 0" class="grid gap-4">
                <template x-for="habit in activeHabits" :key="habit.id">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900" x-text="habit.nombre"></h4>
                                <p class="text-sm text-gray-500 capitalize" x-text="habit.categoria"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900" x-text="`Día ${habit.current_day} de ${habit.duration_days}`"></div>
                                <div class="text-xs text-gray-500" x-text="`${habit.remaining_days} días restantes`"></div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span x-text="`${habit.progress_percentage}%`"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-success h-2 rounded-full transition-all duration-500"
                                     :style="`width: ${habit.progress_percentage}%`"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span x-text="`Racha: ${habit.dias_racha} días`"></span>
                            </div>
                            <button @click="completeHabit(habit)"
                                    :disabled="!habit.can_complete"
                                    :class="habit.can_complete ? 'bg-success hover:bg-green-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    class="px-4 py-2 rounded-lg font-medium transition-colors">
                                <span x-text="habit.completed_today ? 'Completado' : 'Marcar Completado'"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- No Active Habits -->
            <div x-show="activeHabits.length === 0" class="text-center py-12">
                <div class="text-gray-400 text-4xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes hábitos activos</h3>
                <p class="text-gray-600 mb-6">Comienza creando tu primer hábito para comenzar tu viaje de mejora personal.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button @click="showCreateModal = true" 
                            class="bg-accent text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-medium transition-colors">
                        Crear Tu Primer Hábito
                    </button>
                    <a href="/quiz" 
                       class="bg-warning text-white px-6 py-3 rounded-lg hover:bg-yellow-600 font-medium transition-colors">
                        Probar Quiz de Hábitos
                    </a>
                </div>
            </div>
        </div>

        <!-- Completed Today Section -->
        <div x-show="completedHabits.length > 0" class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Completados Hoy</h3>
            <div class="grid gap-3">
                <template x-for="habit in completedHabits" :key="habit.id">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-green-900" x-text="habit.nombre"></h4>
                                <p class="text-sm text-green-700" x-text="`Día ${habit.current_day} de ${habit.duration_days}`"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-green-900">✓ Completado</div>
                                <div class="text-xs text-green-700" x-text="`a las ${habit.completed_at}`"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Habit Suggestions -->
        <div x-show="activeHabits.length === 0 && suggestions.popular.length > 0" class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Hábitos Populares</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <template x-for="suggestion in suggestions.popular.slice(0, 4)" :key="suggestion.id">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h4 class="font-medium text-gray-900 mb-2" x-text="suggestion.name"></h4>
                        <p class="text-sm text-gray-600 mb-3" x-text="suggestion.description"></p>
                        <button @click="adoptSuggestion(suggestion)"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-900 px-3 py-2 rounded text-sm font-medium transition-colors">
                            Adoptar Hábito
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <!-- Create Habit Modal -->
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
             class="bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Crear Nuevo Hábito</h2>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <form @submit.prevent="submitCreateForm()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del hábito</label>
                    <input type="text" 
                           x-model="createForm.name"
                           placeholder="Ej: Hacer ejercicio, Leer 30 minutos..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea x-model="createForm.description"
                              placeholder="Describe brevemente tu hábito..."
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select x-model="createForm.category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent">
                            <option value="salud">Salud</option>
                            <option value="productividad">Productividad</option>
                            <option value="bienestar">Bienestar</option>
                            <option value="aprendizaje">Aprendizaje</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duración</label>
                        <select x-model="createForm.duration_days" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent">
                            <option value="21">21 días</option>
                            <option value="30">30 días</option>
                            <option value="60">60 días</option>
                            <option value="90">90 días</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivación</label>
                    <textarea x-model="createForm.motivation"
                              placeholder="¿Por qué quieres desarrollar este hábito?"
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"
                              required></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            @click="showCreateModal = false"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-blue-600 font-medium">
                        Crear Hábito
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm z-50">
        <p class="text-sm text-gray-900" x-text="notification.message"></p>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function habitApp() {
            return {
                activeHabits: [],
                completedHabits: [],
                suggestions: { popular: [] },
                showCreateModal: false,
                createForm: {
                    name: '',
                    description: '',
                    category: 'bienestar',
                    duration_days: 30,
                    motivation: '',
                    frequency: 'diario'
                },
                userStats: {
                    xp: {{ auth()->user()->xp ?? 0 }},
                    level: {{ auth()->user()->level ?? 1 }},
                    progress: {{ auth()->user()->getLevelProgress() ?? 0 }},
                    next_level_xp: {{ auth()->user()->getXpForNextLevel() ?? 100 }}
                },
                motivationMessage: {
                    title: 'Bienvenido de vuelta',
                    content: 'Mantente enfocado en tus objetivos de hoy.'
                },
                notification: {
                    show: false,
                    message: ''
                },

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
                    this.loadUserHabits();
                    this.loadSuggestions();
                    this.updateMotivationMessage();
                },

                async loadUserHabits() {
                    try {
                        const response = await fetch('/api/user-habits');
                        const data = await response.json();
                        
                        this.activeHabits = data.active_habits;
                        this.completedHabits = data.completed_today;
                        this.userStats = data.user_stats;
                        this.updateMotivationMessage();
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
                            
                            // Actualizar stats del usuario
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            
                            // Verificar si subió de nivel y mostrar confetti
                            if (data.leveled_up) {
                                this.launchConfetti();
                                this.showNotification(`¡Felicidades! Has subido al nivel ${data.new_level}!`);
                            }
                            
                            this.loadUserHabits();
                        } else {
                            this.showNotification(data.message);
                        }
                    } catch (error) {
                        console.error('Error completing habit:', error);
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
                                motivation: this.createForm.motivation,
                                duration_days: this.createForm.duration_days
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            this.showCreateModal = false;
                            this.resetCreateForm();
                            
                            // Actualizar stats del usuario
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            
                            // Verificar si subió de nivel y mostrar confetti
                            if (data.leveled_up) {
                                this.launchConfetti();
                                this.showNotification(`¡Felicidades! Has subido al nivel ${data.new_level}!`);
                            }
                            
                            this.loadUserHabits();
                        } else {
                            this.showNotification(data.message || 'Error al crear el hábito');
                        }
                    } catch (error) {
                        console.error('Error creating habit:', error);
                        this.showNotification('Error al crear el hábito');
                    }
                },

                resetCreateForm() {
                    this.createForm = {
                        name: '',
                        description: '',
                        category: 'bienestar',
                        duration_days: 30,
                        motivation: '',
                        frequency: 'diario'
                    };
                },

                async adoptSuggestion(suggestion) {
                    try {
                        const response = await fetch('/habits/create-from-suggestion', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                suggestion_id: suggestion.id,
                                duration_days: 30,
                                frequency: 'diario'
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message);
                            
                            // Actualizar stats del usuario
                            if (data.user_stats) {
                                this.userStats = data.user_stats;
                            }
                            
                            // Verificar si subió de nivel y mostrar confetti
                            if (data.leveled_up) {
                                this.launchConfetti();
                                this.showNotification(`¡Felicidades! Has subido al nivel ${data.new_level}!`);
                            }
                            
                            this.loadUserHabits();
                            this.loadSuggestions();
                        } else {
                            this.showNotification(data.message || 'Error al adoptar el hábito');
                        }
                    } catch (error) {
                        console.error('Error adopting suggestion:', error);
                        this.showNotification('Error al adoptar el hábito');
                    }
                },

                updateMotivationMessage() {
                    if (this.completedHabits.length > 0) {
                        const habit = this.completedHabits[0];
                        this.motivationMessage = {
                            title: 'Excelente trabajo!',
                            content: `Completaste tu hábito de ${habit.nombre} hoy.`
                        };
                    } else if (this.activeHabits.length > 0) {
                        const habitsWithStreak = this.activeHabits.filter(h => h.dias_racha > 1);
                        if (habitsWithStreak.length > 0) {
                            const habit = habitsWithStreak[0];
                            this.motivationMessage = {
                                title: 'Sigue así!',
                                content: `Llevas ${habit.dias_racha} días seguidos con ${habit.nombre}.`
                            };
                        } else {
                            this.motivationMessage = {
                                title: 'Es hora de actuar',
                                content: 'Completa tus hábitos de hoy para mantener tu progreso.'
                            };
                        }
                    } else {
                        this.motivationMessage = {
                            title: 'Comienza tu viaje',
                            content: 'Crea tu primer hábito y da el primer paso hacia tus objetivos.'
                        };
                    }
                },

                showNotification(message) {
                    this.notification.message = message;
                    this.notification.show = true;
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
