<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Hábitos - Transformación Personal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .habit-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .habit-card:hover {
            transform: translateY(-2px);
        }
        
        .habit-expanded {
            animation: expandCard 0.3s ease-out;
        }
        
        .habit-collapsed {
            animation: collapseCard 0.3s ease-out;
        }
        
        @keyframes expandCard {
            from {
                max-height: 200px;
                opacity: 0.8;
            }
            to {
                max-height: 800px;
                opacity: 1;
            }
        }
        
        @keyframes collapseCard {
            from {
                max-height: 800px;
                opacity: 1;
            }
            to {
                max-height: 200px;
                opacity: 0.8;
            }
        }
        
        .step-list {
            counter-reset: step-counter;
        }
        
        .step-item {
            counter-increment: step-counter;
            position: relative;
            padding-left: 3rem;
            margin-bottom: 1rem;
        }
        
        .step-item::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .category-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
        
        .xp-animation {
            animation: xpGain 0.6s ease-out;
        }
        
        @keyframes xpGain {
            0% { transform: scale(1) translateY(0); opacity: 1; }
            50% { transform: scale(1.2) translateY(-10px); opacity: 0.8; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        
        .level-up-animation {
            animation: levelUp 1s ease-out;
        }
        
        @keyframes levelUp {
            0% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1.05); }
            75% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .completed-habit {
            opacity: 0.7;
            background: linear-gradient(135deg, #a8e6cf 0%, #dcedc1 100%);
        }
        
        .suggested-habit {
            border: 2px dashed #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 min-h-screen">
    <!-- Header con stats del usuario -->
    <header class="bg-white shadow-lg border-b-4 border-purple-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mis Hábitos</h1>
                    <p class="text-gray-600 mt-1">Construye la mejor versión de ti mismo</p>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Stats del usuario -->
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="user-level">{{ $userStats['level'] }}</div>
                        <div class="text-sm text-gray-500">Nivel</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="user-xp">{{ $userStats['xp'] }}</div>
                        <div class="text-sm text-gray-500">XP Total</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="completed-today">{{ $userStats['completed_today'] }}</div>
                        <div class="text-sm text-gray-500">Hoy</div>
                    </div>
                    
                    <!-- Barra de progreso de nivel -->
                    <div class="w-32">
                        <div class="text-xs text-gray-500 mb-1">Progreso al nivel {{ $userStats['level'] + 1 }}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $userStats['progress'] }}%" id="level-progress"></div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">{{ $userStats['xp'] }}/{{ $userStats['next_level_xp'] }} XP</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna principal: Mis Hábitos -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-list-check text-purple-500 mr-3"></i>
                            Mis Hábitos Activos
                        </h2>
                        <a href="{{ route('formulario_habito.show') }}" class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all" data-action="create-habit">
                            <i class="fas fa-plus mr-2"></i>Crear Nuevo
                        </a>
                    </div>
                    
                    <div id="user-habits-container" class="space-y-4">
                        @forelse($userHabits as $habit)
                            @include('habits.partials.habit-card', ['habit' => $habit, 'type' => 'user'])
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-seedling text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-semibold text-gray-500 mb-2">¡Comienza tu transformación!</h3>
                                <p class="text-gray-400 mb-4">Aún no tienes hábitos. Agrega uno sugerido o crea tu propio hábito personalizado.</p>
                                <div class="space-x-3">
                                    <a href="{{ route('formulario_habito.show') }}" class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all inline-block">
                                        <i class="fas fa-plus mr-2"></i>Crear Mi Primer Hábito
                                    </a>
                                    <button class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all" data-action="explore-suggestions">
                                        <i class="fas fa-lightbulb mr-2"></i>Ver Sugerencias
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar: Hábitos Sugeridos -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-lightbulb text-yellow-500 mr-3"></i>
                        Hábitos Sugeridos
                    </h2>
                    
                    <div id="suggested-habits-container" class="space-y-4">
                        @if(count($suggestedHabits) > 0)
                            @foreach($suggestedHabits as $suggestion)
                                @include('habits.partials.suggestion-card', ['suggestion' => $suggestion])
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-lightbulb text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500 mb-4">No hay sugerencias disponibles</p>
                                <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                    Recargar
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-700 mb-3">Beneficios de crear hábitos:</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                Gana XP y sube de nivel
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-trophy text-gold-400 mr-2"></i>
                                Desbloquea logros
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-chart-line text-green-400 mr-2"></i>
                                Seguimiento de progreso
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de confirmación -->
    <div id="confirm-modal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Completar hábito?</h3>
                <p class="text-gray-600 mb-6" id="confirm-habit-name"></p>
                <div class="flex space-x-3">
                    <button id="cancel-complete" class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button id="confirm-complete" class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-2 px-4 rounded-lg hover:shadow-lg transition-all">
                        ¡Completar! (+20 XP)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de level up -->
    <div id="levelup-modal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gradient-to-br from-yellow-400 via-orange-400 to-red-400 rounded-2xl p-8 max-w-md w-full mx-4 text-white text-center transform transition-all level-up-animation">
            <div class="text-6xl mb-4">🎉</div>
            <h2 class="text-2xl font-bold mb-2">¡LEVEL UP!</h2>
            <p class="text-lg mb-4">¡Has alcanzado el nivel <span id="new-level"></span>!</p>
            <button id="close-levelup" class="bg-white text-orange-500 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                ¡Genial!
            </button>
        </div>
    </div>

    <script src="{{ asset('js/habits-manager.js') }}"></script>
</body>
</html>
