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
             class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl max-h-[85vh] overflow-hidden">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center">
                        <i class="fas fa-magic text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Hábito</h2>
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
            <div class="px-8 pt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
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
                                            <div class="text-2xl mb-2"><i class="fas fa-calendar-day text-blue-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-chart-bar text-purple-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-heartbeat text-red-500"></i></div>
                                            <div class="font-semibold">Salud</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="productividad" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'productividad' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-briefcase text-blue-500"></i></div>
                                            <div class="font-semibold">Productividad</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="bienestar" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'bienestar' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-smile text-yellow-500"></i></div>
                                            <div class="font-semibold">Bienestar</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="createForm.category" value="aprendizaje" class="sr-only">
                                    <div class="w-full p-4 border-2 rounded-lg cursor-pointer transition-all"
                                         :class="createForm.category === 'aprendizaje' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2"><i class="fas fa-book text-green-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-clock text-orange-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-calendar-alt text-green-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-bullseye text-purple-500"></i></div>
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
                                            <div class="text-2xl mb-2"><i class="fas fa-trophy text-yellow-500"></i></div>
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
                                <i class="fas fa-rocket mr-1"></i>Crear Hábito
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
