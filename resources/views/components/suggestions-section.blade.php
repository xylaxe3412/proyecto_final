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
            <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 
                       transition-all duration-500 cursor-pointer transform hover:scale-105 hover:-translate-y-2 
                       hover:shadow-xl hover:shadow-motiveo-primary/20 animate-card-appear group"
                 :class="{
                     'hover:bg-white/10': !suggestion.already_added,
                     'opacity-60 cursor-not-allowed hover:scale-100 hover:translate-y-0': suggestion.already_added
                 }"
                 :style="`animation-delay: ${index * 0.1}s`"
                 @click="suggestion.already_added ? null : adoptSuggestion(suggestion)"
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
                <button class="w-full py-2 px-3 rounded-lg text-xs font-medium 
                               transition-all duration-300 transform hover:scale-105 hover:shadow-lg overflow-hidden relative"
                        :class="{
                            'bg-motiveo-accent/20 text-motiveo-accent hover:bg-motiveo-accent hover:text-white': !suggestion.already_added,
                            'bg-green-500/20 text-green-400 cursor-not-allowed': suggestion.already_added
                        }"
                        :disabled="suggestion.already_added"
                        @click.stop="suggestion.already_added ? null : adoptSuggestion(suggestion)">
                    <span x-show="!suggestion.already_added" class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-plus mr-1 group-hover:rotate-90 transition-transform duration-300"></i>Adoptar
                    </span>
                    <span x-show="suggestion.already_added" class="relative z-10 flex items-center justify-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Agregado</span>
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
