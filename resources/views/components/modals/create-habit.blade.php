<!-- Modal de Crear Nuevo Hábito -->
<div x-show="showCreateModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto"
     @click.self="showCreateModal = false">
    
    <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="showCreateModal = false"
             class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl max-w-4xl w-full overflow-hidden border border-gray-200 dark:border-gray-700">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center">
                        <i class="fas fa-magic text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Hábito</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="`Paso ${createForm.step} de 5`"></p>
                    </div>
                </div>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="px-8 pt-4">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary h-2 rounded-full transition-all duration-300"
                         :style="`width: ${(createForm.step / 5) * 100}%`"></div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="overflow-y-auto" style="max-height: calc(85vh - 140px);">
                <form @submit.prevent="submitCreateForm()" class="p-8">
                    
                    <!-- Paso 1: Información Básica -->
                    <div x-show="createForm.step === 1" class="space-y-8">
                        <div class="text-center mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¿Qué hábito quieres desarrollar?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Comencemos con la información básica de tu nuevo hábito.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del hábito</label>
                            <input type="text" 
                                   x-model="createForm.name"
                                   placeholder="Ej: Hacer ejercicio, Leer 30 minutos, Meditar..."
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                            <textarea x-model="createForm.description"
                                      placeholder="Describe brevemente en qué consiste este hábito..."
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    <!-- Paso 2: Frecuencia y Categoría -->
                    <div x-show="createForm.step === 2" class="space-y-6">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¿Con qué frecuencia lo harás?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Elige la frecuencia y categoría que mejor se adapte a tu objetivo.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Frecuencia</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.frequency" value="diario" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.frequency === 'diario' ? 'border-motiveo-primary bg-motiveo-primary/5 dark:bg-motiveo-primary/10' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-calendar-day text-blue-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Diario</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">Todos los días</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.frequency" value="semanal" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.frequency === 'semanal' ? 'border-motiveo-primary bg-motiveo-primary/5 dark:bg-motiveo-primary/10' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-chart-bar text-purple-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Semanal</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">Una vez por semana</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Selector de días para frecuencia semanal -->
                            <div x-show="createForm.frequency === 'semanal'" class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona los días:</label>
                                <div class="grid grid-cols-7 gap-2">
                                    <template x-for="(day, index) in ['L', 'M', 'X', 'J', 'V', 'S', 'D']" :key="index">
                                        <button type="button"
                                            @click.prevent="toggleDay(index)"
                                            :class="{
                                                'bg-motiveo-primary text-white': Array.isArray(createForm.selectedDays) && createForm.selectedDays.includes(index),
                                                'bg-white text-gray-700 hover:bg-gray-100': !createForm.selectedDays.includes(index)
                                            }"
                                            class="p-2 rounded-lg border border-gray-300 font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:ring-opacity-50"
                                            x-text="day">
                                        </button>
                                    </template>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle"></i>
                                    Selecciona los días en los que realizarás este hábito
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Categoría</label>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="salud" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'salud' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-heartbeat text-red-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Salud</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="productividad" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'productividad' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-briefcase text-blue-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Productividad</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="bienestar" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'bienestar' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-smile text-yellow-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Bienestar</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="aprendizaje" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'aprendizaje' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-book text-green-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Aprendizaje</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="finanzas" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'finanzas' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-dollar-sign text-green-600"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Finanzas</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="relaciones" class="sr-only" @change="generateMotivationalPhrase()">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'relaciones' ? 'border-pink-500 bg-pink-50 dark:bg-pink-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-800'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-heart text-pink-500"></i></div>
                                            <div class="font-semibold text-gray-900 dark:text-white">Relaciones</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Duración</label>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.duration_days" value="21" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.duration_days === '21' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-clock text-orange-500"></i></div>
                                            <div class="font-semibold">21 días</div>
                                            <div class="text-sm text-gray-600">Rápido</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.duration_days" value="30" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.duration_days === '30' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-calendar-alt text-green-500"></i></div>
                                            <div class="font-semibold">30 días</div>
                                            <div class="text-sm text-gray-600">Recomendado</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.duration_days" value="60" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.duration_days === '60' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-bullseye text-purple-500"></i></div>
                                            <div class="font-semibold">60 días</div>
                                            <div class="text-sm text-gray-600">Desafío</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.duration_days" value="90" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.duration_days === '90' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-trophy text-yellow-500"></i></div>
                                            <div class="font-semibold">90 días</div>
                                            <div class="text-sm text-gray-600">Experto</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.duration_days" value="custom" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.duration_days === 'custom' ? 'border-motiveo-primary bg-motiveo-primary/5' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-edit text-blue-500"></i></div>
                                            <div class="font-semibold">Personalizar</div>
                                            <div class="text-sm text-gray-600">Tu elección</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Input para duración personalizada -->
                            <div x-show="createForm.duration_days === 'custom'" x-transition class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Días específicos</label>
                                <div class="relative">
                                    <input type="number" 
                                           x-model="createForm.custom_duration"
                                           min="7" 
                                           max="365" 
                                           placeholder="Ej: 45"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <span class="text-gray-500 text-sm">días</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Entre 7 y 365 días (recomendado: 21-90 días)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Motivación -->
                    <div x-show="createForm.step === 3" x-init="if (createForm.step === 3 && !createForm.generated_motivation) generateMotivationalPhrase()" class="space-y-6">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¿Qué te motiva a crear este hábito?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Entender tu motivación te ayudará a mantener la constancia.</p>
                        </div>
                        
                        <!-- Generador de frases motivadoras -->
                        <div class="bg-gradient-to-r from-motiveo-primary/5 to-motiveo-secondary/5 dark:from-motiveo-primary/10 dark:to-motiveo-secondary/10 p-4 rounded-lg border border-motiveo-primary/20 dark:border-motiveo-primary/30">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                    Frase Motivadora Personalizada
                                </h4>
                                <button type="button" 
                                        @click="generateMotivationalPhrase()"
                                        class="px-3 py-1 bg-motiveo-primary text-white rounded-md text-sm hover:bg-motiveo-primary/80 transition-colors">
                                    <i class="fas fa-sync-alt mr-1"></i>Generar
                                </button>
                            </div>
                            
                            <div x-show="createForm.generated_motivation" x-transition class="mb-3">
                                <div class="bg-white dark:bg-gray-800 p-3 rounded border-l-4 border-motiveo-primary">
                                    <p class="text-gray-700 dark:text-gray-300 italic" x-text="createForm.generated_motivation"></p>
                                </div>
                                <button type="button" 
                                        @click="useGeneratedMotivation()"
                                        class="mt-2 text-sm text-motiveo-primary hover:text-motiveo-primary/80 transition-colors">
                                    <i class="fas fa-arrow-down mr-1"></i>Usar esta frase
                                </button>
                            </div>
                            
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Basada en tu categoría: <span class="font-semibold capitalize" x-text="createForm.category"></span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tu motivación personal</label>
                            <textarea x-model="createForm.motivation"
                                      placeholder="Ej: Quiero sentirme más saludable, mejorar mi concentración, desarrollar una nueva habilidad..."
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary"
                                      required></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Puedes escribir tu propia motivación o usar la frase generada arriba
                            </p>
                        </div>
                    </div>

                    <!-- Paso 4: Recompensa -->
                    <div x-show="createForm.step === 4" class="space-y-6">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¿Cómo te recompensarás?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Una recompensa personal te ayudará a mantener la motivación.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tu recompensa</label>
                            <textarea x-model="createForm.reward"
                                      placeholder="Ej: Ver una película, comprar algo especial, salir con amigos, un día de descanso..."
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    <!-- Paso 5: Fecha de inicio -->
                    <div x-show="createForm.step === 5" class="space-y-6">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¿Cuándo empezarás?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Elige una fecha para comenzar tu nuevo hábito.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de inicio</label>
                            <input type="date" 
                                   x-model="createForm.start_date"
                                   :min="new Date().toISOString().split('T')[0]"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                   required>
                        </div>

                        <!-- Resumen -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Resumen de tu hábito:</h4>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Nombre:</span> <span x-text="createForm.name"></span></p>
                                <p><span class="font-medium">Frecuencia:</span> <span x-text="createForm.frequency"></span></p>
                                <p><span class="font-medium">Categoría:</span> <span x-text="createForm.category"></span></p>
                                <p><span class="font-medium">Inicio:</span> <span x-text="createForm.start_date"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                @click="createForm.step > 1 ? createForm.step-- : (showCreateModal = false)"
                                class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
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
                                <i class="fas fa-rocket mr-1"></i>Crear Hábito
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
