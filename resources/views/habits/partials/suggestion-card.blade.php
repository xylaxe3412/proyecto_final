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
    
    $iconClass = $categoryIcons[$suggestion->categoria] ?? 'fas fa-star';
    $colorClass = $categoryColors[$suggestion->categoria] ?? 'from-gray-500 to-slate-500';
@endphp

<div class="habit-card suggested-habit bg-white rounded-xl shadow-lg p-4 cursor-pointer border-2 border-dashed border-gray-200 hover:border-purple-300 hover:shadow-xl transition-all"
     data-habit-id="{{ $suggestion->id }}" 
     data-habit-type="suggested">
     
    <!-- Header de la tarjeta -->
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center flex-1">
            <div class="w-10 h-10 bg-gradient-to-r {{ $colorClass }} rounded-full flex items-center justify-center text-white mr-3">
                <i class="{{ $iconClass }}"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 text-sm">{{ $suggestion->name ?? 'Hábito Sugerido' }}</h4>
                <p class="text-gray-600 text-xs">{{ Str::limit($suggestion->description ?? 'Descripción del hábito', 50) }}</p>
            </div>
        </div>
        
        <!-- Indicador de popularidad -->
        <div class="text-right">
            <div class="text-xs text-gray-500">{{ $suggestion->popularity ?? 0 }} usuarios</div>
            <div class="flex items-center text-yellow-400">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-xs {{ $i <= min(5, floor(($suggestion->popularity ?? 0) / 20)) ? '' : 'text-gray-300' }}"></i>
                @endfor
            </div>
        </div>
    </div>

    <!-- Badge de categoría y botón -->
    <div class="flex items-center justify-between mb-3">
        <span class="bg-gradient-to-r {{ $colorClass }} text-white text-xs px-2 py-1 rounded-full">
            {{ ucfirst($suggestion->categoria ?? 'General') }}
        </span>
        
        <button class="add-suggested-btn bg-gradient-to-r from-purple-500 to-blue-500 text-white px-3 py-1 rounded-lg text-xs hover:shadow-lg transition-all font-semibold">
            <i class="fas fa-plus mr-1"></i>Agregar
        </button>
    </div>

    <!-- Contenido expandido (oculto por defecto) -->
    <div class="habit-expanded-content" style="display: none;">
        <div class="border-t border-gray-200 pt-3 mt-3">
            <!-- Descripción completa -->
            <p class="text-gray-700 text-sm mb-4">{{ $suggestion->description ?? 'Este hábito te ayudará en tu crecimiento personal.' }}</p>
            
            <!-- Beneficios -->
            @if($suggestion->benefits ?? false)
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-900 text-sm mb-2 flex items-center">
                        <i class="fas fa-thumbs-up text-green-500 mr-2"></i>
                        Beneficios:
                    </h5>
                    <p class="text-gray-600 text-sm">{{ $suggestion->benefits }}</p>
                </div>
            @endif
            
            <!-- Pasos sugeridos -->
            <div class="mb-4">
                <h5 class="font-semibold text-gray-900 text-sm mb-2 flex items-center">
                    <i class="fas fa-list-ol text-purple-500 mr-2"></i>
                    Cómo empezar:
                </h5>
                
                <div class="space-y-2">
                    <div class="flex items-start text-xs text-gray-700">
                        <span class="w-5 h-5 bg-gradient-to-r {{ $colorClass }} text-white rounded-full flex items-center justify-center mr-2 mt-0.5 text-xs font-bold">1</span>
                        <span class="flex-1">{{ $suggestion->description ?? 'Comienza con este hábito gradualmente' }}</span>
                    </div>
                    <div class="flex items-start text-xs text-gray-700">
                        <span class="w-5 h-5 bg-gradient-to-r {{ $colorClass }} text-white rounded-full flex items-center justify-center mr-2 mt-0.5 text-xs font-bold">2</span>
                        <span class="flex-1">Establece un horario específico para realizar esta actividad</span>
                    </div>
                    <div class="flex items-start text-xs text-gray-700">
                        <span class="w-5 h-5 bg-gradient-to-r {{ $colorClass }} text-white rounded-full flex items-center justify-center mr-2 mt-0.5 text-xs font-bold">3</span>
                        <span class="flex-1">Registra tu progreso diariamente</span>
                    </div>
                </div>
            </div>
            
            <!-- Frecuencia sugerida -->
            @if($suggestion->frequency_suggestions ?? false)
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-900 text-sm mb-2">Frecuencia recomendada:</h5>
                    <div class="flex flex-wrap gap-1">
                        @if(is_array($suggestion->frequency_suggestions))
                            @foreach($suggestion->frequency_suggestions as $freq)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $freq }}</span>
                            @endforeach
                        @else
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Diario</span>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Botón de acción principal -->
            <button class="add-suggested-btn w-full bg-gradient-to-r from-purple-500 to-blue-500 text-white py-2 px-4 rounded-lg hover:shadow-lg transition-all font-semibold text-sm">
                <i class="fas fa-plus mr-2"></i>Agregar a mis hábitos (+10 XP)
            </button>
        </div>
    </div>
</div>
