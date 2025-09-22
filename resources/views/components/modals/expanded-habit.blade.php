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
