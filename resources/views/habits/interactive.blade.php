<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Hábitos - Vista Principal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <style>
        /* Estilos para las tarjetas de hábitos */
        .habit-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .habit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Hábitos pendientes - Resaltados */
        .habit-pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid #f59e0b;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
            animation: pulseGlow 2s infinite;
        }
        
        /* Hábitos completados */
        .habit-completed {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 2px solid #10b981;
            opacity: 0.8;
        }
        
        /* Animación de resaltado para hábitos pendientes */
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); }
            50% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.5); }
        }
        
        /* Vista expandida */
        .habit-expanded {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            width: 90vw;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: expandToCenter 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes expandToCenter {
            from {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
        
        /* Backdrop para vista expandida */
        .habit-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 40;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Pasos del hábito */
        .step-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .step-item:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            transform: translateX(4px);
        }
        
        .step-completed {
            background: rgba(34, 197, 94, 0.1);
            border-color: #22c55e;
        }
        
        .step-completed .step-checkbox {
            background: #22c55e;
            color: white;
        }
        
        /* Animaciones de confirmación */
        .completion-pulse {
            animation: completionPulse 0.8s ease-out;
        }
        
        @keyframes completionPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(34, 197, 94, 0.5); }
            100% { transform: scale(1); }
        }
        
        /* Botón de doble confirmación */
        .confirm-button {
            position: relative;
            overflow: hidden;
        }
        
        .confirm-button.confirming {
            background: #dc2626;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Grid responsivo */
        .habits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .habits-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading states */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Notificaciones */
        .notification {
            animation: slideInRight 0.4s ease-out;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">
    <!-- Header Mejorado -->
    <header class="bg-white shadow-lg border-b sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-bullseye text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Panel de Hábitos</h1>
                        <p class="text-sm text-gray-600">Organiza y completa tus hábitos diarios</p>
                    </div>
                </div>
                
                <!-- Stats del Usuario -->
                <div class="flex items-center space-x-6">
                    <div class="hidden sm:flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600" id="habits-today">0</div>
                            <div class="text-xs text-gray-500">Hoy</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600" id="habits-completed">0</div>
                            <div class="text-xs text-gray-500">Completados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600" id="current-streak">0</div>
                            <div class="text-xs text-gray-500">Racha</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">
                            NIVEL {{ auth()->user()->level ?? 1 }}
                        </div>
                        <button id="create-habit-btn" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all font-medium">
                            <i class="fas fa-plus mr-2"></i>Nuevo Hábito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-blue-500 rounded-full transition-all duration-500" 
                                     style="width: {{ auth()->user()->getLevelProgress() ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ auth()->user()->xp ?? 0 }}/{{ auth()->user()->getXpForNextLevel() ?? 100 }} XP
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" id="habits-app">
        <!-- Loading State -->
        <div id="loading-state" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            <p class="text-gray-600 mt-4 text-lg">Cargando tus hábitos...</p>
        </div>

        <!-- Content Container -->
        <div id="content-container" class="hidden">
            <!-- Sección Principal - Grid de Hábitos -->
            <section class="mb-12">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Mis Hábitos Diarios</h2>
                        <p class="text-gray-600">Haz clic en cualquier hábito para ver los detalles y pasos</p>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="flex items-center space-x-4">
                        <select id="filter-status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">Todos los hábitos</option>
                            <option value="pending">Pendientes</option>
                            <option value="completed">Completados</option>
                        </select>
                        <select id="filter-category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">Todas las categorías</option>
                            <option value="salud">Salud</option>
                            <option value="productividad">Productividad</option>
                            <option value="bienestar">Bienestar</option>
                            <option value="aprendizaje">Aprendizaje</option>
                        </select>
                    </div>
                </div>
                
                <!-- Grid de Tarjetas de Hábitos -->
                <div id="user-habits-container" class="habits-grid">
                    <!-- Los hábitos del usuario se cargarán aquí -->
                </div>
                
                <!-- Empty State -->
                <div id="user-habits-empty" class="hidden text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-seedling text-gray-300 text-6xl mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-500 mb-4">¡Comienza tu transformación!</h3>
                        <p class="text-gray-400 mb-8">Aún no tienes hábitos. Crea uno nuevo o explora nuestras sugerencias personalizadas.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button id="create-first-habit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all font-semibold">
                                <i class="fas fa-plus mr-2"></i>Crear Mi Primer Hábito
                            </button>
                            <button id="explore-suggestions" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all font-semibold">
                                <i class="fas fa-lightbulb mr-2"></i>Ver Sugerencias
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Hábitos Sugeridos -->
            <section id="suggested-habits-section" class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Hábitos Sugeridos</h2>
                        <p class="text-gray-600">Recomendaciones personalizadas para ti</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Categorías -->
                        <div id="categories-container" class="flex items-center space-x-2">
                            <!-- Las categorías se cargarán aquí -->
                        </div>
                        <button id="refresh-suggestions" class="text-green-600 hover:text-green-700 font-medium transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Renovar
                        </button>
                    </div>
                </div>
                
                <div id="suggested-habits-container" class="habits-grid">
                    <!-- Las sugerencias se cargarán aquí -->
                </div>
            </section>
        </div>
    </main>

    <!-- Vista Expandida de Hábito -->
    <div id="habit-detail-backdrop" class="habit-backdrop hidden" onclick="closeHabitDetail()"></div>
    <div id="habit-detail-modal" class="hidden">
        <!-- El contenido del hábito expandido se cargará aquí dinámicamente -->
    </div>

    <!-- Modal de Confirmación con Doble Click -->
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-8 transform transition-all">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">¿Completaste este hábito?</h3>
                <p class="text-gray-600 mb-3">Estás a punto de marcar como completado:</p>
                <p id="confirm-habit-name" class="font-bold text-blue-600 text-lg mb-6">Nombre del hábito</p>
                
                <!-- Progreso de pasos completados -->
                <div id="steps-progress" class="mb-6 hidden">
                    <div class="flex items-center justify-center space-x-2 mb-3">
                        <i class="fas fa-list-check text-gray-500"></i>
                        <span class="text-sm text-gray-600">Pasos completados:</span>
                        <span id="steps-completed-count" class="font-bold text-green-600">0/0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="steps-progress-bar" class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">⚠️ Para confirmar, haz clic dos veces en "Completar"</p>
                </div>
                
                <div class="flex space-x-4">
                    <button id="cancel-complete" class="flex-1 px-6 py-3 text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium">
                        Cancelar
                    </button>
                    <button id="confirm-complete" class="confirm-button flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold">
                        <span id="confirm-text">
                            <i class="fas fa-check mr-2"></i>Completar (1/2)
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Level Up -->
    <div id="level-up-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-8 text-center transform transition-all">
            <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-crown text-white text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-3">¡Subiste de Nivel!</h2>
            <p class="text-gray-600 mb-4">Ahora eres nivel <span id="new-level" class="font-bold text-2xl text-yellow-600">1</span></p>
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 mb-6 border border-yellow-200">
                <p class="text-gray-700 font-medium">🎉 Has desbloqueado nuevas funciones y recompensas</p>
                <p class="text-sm text-gray-600 mt-2">Continúa completando hábitos para seguir creciendo</p>
            </div>
            <button id="close-level-up" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-3 rounded-xl hover:shadow-lg transition-all font-semibold">
                <i class="fas fa-rocket mr-2"></i>¡Continuar!
            </button>
        </div>
    </div>

    <!-- Sistema de Notificaciones Mejorado -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Las notificaciones se agregarán aquí dinámicamente -->
    </div>

    <!-- XP Gain Animation -->
    <div id="xp-gain" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden text-center z-50">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold text-xl shadow-2xl">
            <i class="fas fa-star mr-2"></i>+<span id="xp-amount">10</span> XP
        </div>
    </div>

    <!-- Progress Celebration -->
    <div id="progress-celebration" class="fixed inset-0 pointer-events-none z-40 hidden">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-6xl font-bold text-green-500 animate-bounce">
                🎉 ¡Completado! 🎉
            </div>
        </div>
    </div>

    <!-- Include the Habits Manager JavaScript -->
    <script src="{{ asset('js/habits-manager.js') }}"></script>
    
    <script>
        // Initialize the Habits Manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const habitsManager = new HabitsManager();
            habitsManager.init();
        });
    </script>
</body>
</html>
