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
