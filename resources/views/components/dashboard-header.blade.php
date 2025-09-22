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
