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
                : 'bg-motiveo-primary/20 text-motiveo-primary border-motiveo-primary/30 hover:bg-motiveo-success/20 hover:text-motiveo-success hover:border-motiveo-success'"
            class="px-4 py-2 rounded-lg font-semibold border transition-all duration-300 group-hover:scale-105">
        <span x-text="habit.is_completed ? 'Deshacer' : 'Completar'"></span>
    </button>
</div>
