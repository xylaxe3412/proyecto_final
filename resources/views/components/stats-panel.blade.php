<!-- Panel de Estadísticas y Hábitos Completados - Layout Horizontal -->
<div class="mt-16 space-y-8">
    <!-- Sección de Estadísticas Principales - Horizontal -->
    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                <i class="fas fa-chart-bar text-white text-sm"></i>
            </div>
            <h2 class="text-xl font-bold text-white">Resumen de Progreso</h2>
        </div>

        <!-- Estadísticas en Grid Horizontal -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="text-center p-6 bg-gradient-to-br from-motiveo-primary/20 to-motiveo-secondary/20 rounded-xl border border-motiveo-primary/30">
                <div class="text-3xl font-bold text-motiveo-primary mb-2" x-text="userStats.xp">
                    {{ auth()->user()->xp ?? 0 }}
                </div>
                <div class="text-white/80 text-sm font-medium">Experiencia Total</div>
                <div class="text-motiveo-primary/60 text-xs mt-1">Puntos acumulados</div>
            </div>

            <div class="text-center p-6 bg-gradient-to-br from-motiveo-success/20 to-emerald-500/20 rounded-xl border border-motiveo-success/30">
                <div class="text-3xl font-bold text-motiveo-success mb-2" x-text="totalHabits">
                    {{ auth()->user()->habits()->count() ?? 0 }}
                </div>
                <div class="text-white/80 text-sm font-medium">Hábitos Totales</div>
                <div class="text-motiveo-success/60 text-xs mt-1">Creados hasta hoy</div>
            </div>

            <div class="text-center p-6 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-xl border border-blue-500/30">
                <div class="text-3xl font-bold text-blue-400 mb-2" x-text="userStats.level">
                    {{ auth()->user()->level ?? 1 }}
                </div>
                <div class="text-white/80 text-sm font-medium">Nivel Actual</div>
                <div class="text-blue-400/60 text-xs mt-1">Tu clasificación</div>
            </div>

            <div class="text-center p-6 bg-gradient-to-br from-motiveo-accent/20 to-orange-500/20 rounded-xl border border-motiveo-accent/30">
                <div class="text-3xl font-bold text-motiveo-accent mb-2" x-text="completedHabits.length">0</div>
                <div class="text-white/80 text-sm font-medium">Completados Hoy</div>
                <div class="text-motiveo-accent/60 text-xs mt-1">Logros del día</div>
            </div>
        </div>

        <!-- Barra de Progreso de Nivel -->
        <div class="bg-white/5 rounded-xl p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">Progreso al Siguiente Nivel</h3>
                        <p class="text-white/60 text-sm">Nivel <span x-text="userStats.level">{{ auth()->user()->level ?? 1 }}</span> → Nivel <span x-text="(userStats.level || 1) + 1">{{ (auth()->user()->level ?? 1) + 1 }}</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-purple-400" x-text="userStats.progress + '%'">{{ auth()->user()->getLevelProgress() ?? 0 }}%</div>
                    <div class="text-white/60 text-xs">Completado</div>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-3">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500"
                     :style="`width: ${userStats.progress || {{ auth()->user()->getLevelProgress() ?? 0 }}}%`"></div>
            </div>
        </div>
    </div>

    <!-- Sección de Hábitos Activos y Completados - Layout Horizontal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Hábitos Pendientes de Hoy -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-8 h-8 bg-motiveo-warning rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Pendientes Hoy</h2>
                    <p class="text-white/60 text-sm" x-text="`${activeHabits.length} hábitos por completar`"></p>
                </div>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto" x-show="activeHabits.length > 0">
                <template x-for="habit in activeHabits" :key="habit.id">
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-xl" x-html="getHabitIcon(habit)"></span>
                                <div>
                                    <h3 class="text-white font-semibold text-sm" x-text="habit.nombre"></h3>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-motiveo-success text-xs flex items-center">
                                            <i class="fas fa-fire mr-1"></i>
                                            <span x-text="`${habit.dias_racha || 0} días`"></span>
                                        </span>
                                        <span class="text-white/60 text-xs">•</span>
                                        <span class="text-white/60 text-xs" x-text="`${habit.remaining_days || 29} días restantes`"></span>
                                    </div>
                                </div>
                            </div>
                            <button @click="completeHabit(habit)"
                                    :disabled="habit.today_completed || !habit.can_complete"
                                    :class="(habit.today_completed || !habit.can_complete) ? 'bg-gray-500' : 'bg-motiveo-success hover:bg-motiveo-success/80'"
                                    class="px-4 py-2 rounded-lg text-xs font-semibold text-white transition-all">
                                <span x-text="habit.today_completed ? 'Completado' : '+20 XP'"></span>
                            </button>
                        </div>
                        
                        <!-- Mini barra de progreso -->
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-gradient-to-r from-motiveo-success to-emerald-400 h-2 rounded-full transition-all duration-500"
                                 :style="`width: ${habit.progress_percentage || 0}%`"></div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="activeHabits.length === 0" class="text-center py-12">
                <div class="text-4xl mb-4 text-motiveo-success">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">¡Todo completado!</h3>
                <p class="text-white/60 text-sm">Excelente trabajo por hoy</p>
            </div>
        </div>

        <!-- Hábitos Completados Hoy -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Completados Hoy</h2>
                    <p class="text-white/60 text-sm" x-text="`${completedHabits.length} hábitos logrados`"></p>
                </div>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto" x-show="completedHabits.length > 0">
                <template x-for="habit in completedHabits" :key="habit.id">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-motiveo-success/10 to-emerald-500/10 rounded-xl border border-motiveo-success/20">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg" x-html="getHabitIcon(habit)"></span>
                            <div>
                                <div class="text-white font-medium text-sm" x-text="habit.nombre"></div>
                                <div class="text-motiveo-success/80 text-xs" x-text="`Completado • Día ${habit.current_day || 1}`"></div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="bg-motiveo-success/20 px-3 py-1 rounded-full">
                                <span class="text-motiveo-success text-xs font-bold">+20 XP</span>
                            </div>
                            <i class="fas fa-check-circle text-motiveo-success"></i>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="completedHabits.length === 0" class="text-center py-12">
                <div class="text-4xl mb-4 text-white/30">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-white/60 font-medium mb-2">Aún no hay completados</h3>
                <p class="text-white/40 text-sm">Completa tus primeros hábitos del día</p>
            </div>
        </div>
    </div>
</div>
