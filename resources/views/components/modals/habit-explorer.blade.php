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
