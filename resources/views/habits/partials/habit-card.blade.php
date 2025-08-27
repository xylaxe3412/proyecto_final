@php
    $categoryIcons = [
        'salud' => 'fas fa-heartbeat',
        'productividad' => 'fas fa-chart-line',
        'bienestar' => 'fas fa-leaf',
        'aprendizaje' => 'fas fa-graduation-cap'
    ];
    $categoryColors = [
        'salud' => 'from-red-500 to-pink-500',
        'productividad' => 'from-blue-500 to-indigo-500',
        'bienestar' => 'from-green-500 to-teal-500',
        'aprendizaje' => 'from-purple-500 to-violet-500'
    ];
    
    $iconClass = $categoryIcons[$habit->categoria] ?? 'fas fa-star';
    $colorClass = $categoryColors[$habit->categoria] ?? 'from-gray-500 to-slate-500';
    $isCompleted = $habit->completed_today ?? false;
    $canComplete = $habit->can_complete ?? !$isCompleted;
@endphp

<div class="habit-card bg-white rounded-xl shadow-lg p-6 cursor-pointer border border-gray-100 hover:shadow-xl {{ $isCompleted ? 'completed-habit' : '' }}"
     data-habit-id="{{ $habit->id }}" 
     data-habit-type="{{ $type }}">
     
    <!-- Header de la tarjeta -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center flex-1">
            <div class="w-12 h-12 bg-gradient-to-r {{ $colorClass }} rounded-full flex items-center justify-center text-white mr-4">
                <i class="{{ $iconClass }}"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 text-lg">{{ $habit->nombre ?? $habit->name }}</h3>
                <p class="text-gray-600 text-sm">{{ Str::limit($habit->description, 60) }}</p>
                
                <!-- Badges -->
                <div class="flex items-center mt-2 space-x-2">
                    <span class="bg-gradient-to-r {{ $colorClass }} text-white text-xs px-2 py-1 rounded-full">
                        {{ ucfirst($habit->categoria) }}
                    </span>
                    @if($habit->dias_racha > 0)
                        <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full flex items-center">
                            <i class="fas fa-fire mr-1"></i>
                            <span class="streak-count">{{ $habit->dias_racha }}</span> días
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Botón de completar -->
        @if($type === 'user')
            <button class="complete-btn {{ $isCompleted ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-green-500 to-teal-500 hover:shadow-lg' }} text-white px-4 py-2 rounded-lg transition-all font-semibold text-sm"
                    {{ $isCompleted ? 'disabled' : '' }}>
                @if($isCompleted)
                    <i class="fas fa-check mr-2"></i>Completado
                @else
                    <i class="fas fa-check-circle mr-2"></i>Completar
                @endif
            </button>
        @endif
    </div>

    <!-- Barra de progreso -->
    @if($type === 'user' && isset($habit->duration_days))
        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Progreso del hábito</span>
                <span>{{ $habit->current_day ?? 0 }}/{{ $habit->duration_days }} días</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="progress-bar bg-gradient-to-r {{ $colorClass }} h-2 rounded-full transition-all duration-500" 
                     style="width: {{ ($habit->current_day ?? 0) / $habit->duration_days * 100 }}%"></div>
            </div>
        </div>
    @endif

    <!-- Contenido expandido (oculto por defecto) -->
    <div class="habit-expanded-content" style="display: none;">
        <div class="border-t border-gray-200 pt-4 mt-4">
            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                <i class="fas fa-list-ol text-purple-500 mr-2"></i>
                Pasos para mejorar este hábito:
            </h4>
            
            @php
                $steps = [];
                if($type === 'user') {
                    $steps = [
                        "Paso 1: " . ($habit->description ?? "Comenzar con {$habit->nombre}"),
                        "Paso 2: Establece un horario específico para realizar esta actividad",
                        "Paso 3: Prepara todo lo necesario con anticipación",
                        "Paso 4: Comienza gradualmente y aumenta la intensidad",
                        "Paso 5: Registra tu progreso diariamente",
                        "Paso 6: Celebra cada pequeño logro para mantener la motivación"
                    ];
                }
            @endphp
            
            <div class="step-list space-y-3">
                @foreach($steps as $step)
                    <div class="step-item text-gray-700">
                        {{ $step }}
                    </div>
                @endforeach
            </div>
            
            <!-- Información adicional -->
            <div class="bg-gray-50 rounded-lg p-4 mt-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if($type === 'user')
                        <div>
                            <span class="font-semibold text-gray-600">Racha actual:</span>
                            <span class="text-gray-900">{{ $habit->dias_racha }} días</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">Días restantes:</span>
                            <span class="text-gray-900">{{ ($habit->duration_days ?? 30) - ($habit->current_day ?? 0) }} días</span>
                        </div>
                        @if($habit->motivation)
                            <div class="col-span-2">
                                <span class="font-semibold text-gray-600">Tu motivación:</span>
                                <p class="text-gray-900 italic">"{{ $habit->motivation }}"</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Botones de acción adicionales -->
            <div class="flex space-x-3 mt-4">
                @if($type === 'user' && $canComplete && !$isCompleted)
                    <button class="complete-btn flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-2 px-4 rounded-lg hover:shadow-lg transition-all font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>Marcar como Completado
                    </button>
                @endif
                
                <button class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>Ver Estadísticas
                </button>
            </div>
        </div>
    </div>
</div>
