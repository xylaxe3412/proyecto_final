<!-- Modal de Editar Hábito -->
<div x-show="showEditModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     @click.self="showEditModal = false">
    
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="showEditModal = false"
         class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
         @click.stop>
        
        <form @submit.prevent="updateHabit()" class="p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900"><i class="fas fa-edit mr-2"></i>Editar Hábito</h2>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulario de Edición -->
            <div class="space-y-6">
                <!-- Nombre del Hábito -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre del Hábito
                    </label>
                    <input type="text" 
                           x-model="editForm.nombre"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                           placeholder="Ej: Hacer ejercicio, Leer 30 minutos, Meditar..."
                           required>
                </div>

                <!-- Categoría -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Categoría
                    </label>
                    <select x-model="editForm.categoria"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                            required>
                        <option value="">Selecciona una categoría</option>
                        <option value="salud"><i class="fas fa-heartbeat mr-2"></i>Salud</option>
                        <option value="productividad"><i class="fas fa-briefcase mr-2"></i>Productividad</option>
                        <option value="bienestar"><i class="fas fa-smile mr-2"></i>Bienestar</option>
                        <option value="aprendizaje"><i class="fas fa-book mr-2"></i>Aprendizaje</option>
                    </select>
                </div>

                <!-- Duración -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Duración del Desafío
                    </label>
                    <select x-model="editForm.duration_days"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                            required>
                        <option value="">Selecciona duración</option>
                        <option value="21">21 días - Hábito básico</option>
                        <option value="30">30 días - Desafío estándar</option>
                        <option value="60">60 días - Transformación profunda</option>
                        <option value="90">90 días - Cambio permanente</option>
                    </select>
                </div>

                <!-- Motivación -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ¿Por qué quieres mantener este hábito?
                    </label>
                    <textarea x-model="editForm.motivation"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                              rows="3"
                              placeholder="Describe qué te motiva a mantener este hábito..."></textarea>
                </div>

                <!-- Recompensa -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ¿Cómo te recompensarás? (Opcional)
                    </label>
                    <input type="text" 
                           x-model="editForm.reward"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-motiveo-primary focus:border-transparent"
                           placeholder="Ej: Ver una película, comprar algo especial...">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <button type="button" 
                        @click="showEditModal = false"
                        class="px-6 py-2 text-gray-600 hover:text-gray-800 font-medium">
                    Cancelar
                </button>
                
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-motiveo-primary to-blue-600 text-white rounded-lg hover:shadow-lg font-medium">
                    <i class="fas fa-save mr-2"></i>Actualizar Hábito
                </button>
            </div>
        </form>
    </div>
</div>
