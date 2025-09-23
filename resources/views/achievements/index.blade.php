<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        userStats: $store.userStats
    }" 
    x-init="if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}" 
    x-effect="localStorage.setItem('darkMode', darkMode); if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}" 
    class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logros - Motiveo</title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'motiveo-primary': '#6366f1',
                        'motiveo-secondary': '#8b5cf6',
                        'motiveo-accent': '#06b6d4',
                        'motiveo-success': '#10b981',
                        'motiveo-warning': '#f59e0b',
                        'motiveo-pink': '#ec4899',
                        'motiveo-dark': '#1e1b4b'
                    },
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard-animations.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/sound-effects.js') }}"></script>
    <style>
        .achievement-card {
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }
        
        .achievement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .progress-bar {
            transition: width 0.7s ease-out !important;
        }
        
        .achievement-unlocked {
            animation: pulseGlow 1s ease-in-out;
        }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        
        .update-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-slate-900 dark:to-purple-600 font-display">
    <!-- Botón flotante para alternar modo claro/oscuro -->
    <button @click="darkMode = !darkMode" class="fixed bottom-6 right-6 z-50 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow-lg rounded-full p-3 transition-colors duration-300 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">
        <span x-show="!darkMode" class="text-gray-800"><i class="fas fa-moon"></i></span>
        <span x-show="darkMode" class="text-yellow-400"><i class="fas fa-sun"></i></span>
    </button>

    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-3">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-motiveo-primary/10 dark:bg-motiveo-primary/20">
                            <i class="fas fa-award text-2xl text-motiveo-primary dark:text-motiveo-accent animate-pulse"></i>
                        </div>
                        Tus Logros
                    </h2>
                    <p class="text-gray-600 dark:text-white/70 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-500 dark:text-yellow-400"></i>
                        Descubre y desbloquea logros completando objetivos
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Indicador de actualización automática -->
                    <div id="auto-update-indicator" class="flex items-center space-x-3 bg-white dark:bg-white/10 rounded-lg px-4 py-2.5 backdrop-blur-md border border-gray-200 dark:border-white/20 shadow-md transition-all duration-300 hover:border-motiveo-accent/50 dark:hover:border-motiveo-accent/50">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-robot text-motiveo-accent dark:text-motiveo-accent/80"></i>
                            <span class="text-gray-700 dark:text-white/80 text-sm">Actualización automática</span>
                        </div>
                        <div id="update-status-icon" class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <button id="pause-toggle" onclick="toggleAutoUpdate()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white text-xs flex items-center gap-1 transition-all duration-300 hover:text-motiveo-accent">
                            <i id="pause-icon" class="fas fa-pause"></i>
                            <span class="text-xs">Pausar</span>
                        </button>
                    </div>
                    <!-- Botón de actualización manual -->
                    <button id="manual-update-btn" onclick="updateAchievementsProgress()" 
                            class="bg-motiveo-accent hover:bg-motiveo-accent/80 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center space-x-2 hover:scale-105">
                        <i class="fas fa-sync-alt animate-spin-slow"></i>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-hand-point-right"></i>
                            Actualizar Ahora
                        </span>
                    </button>
                </div>
            </div>
        </div>

        @foreach ($achievementsByCategory as $type => $category)
            <div class="bg-white dark:bg-white/10 backdrop-blur-md rounded-xl p-6 mb-8 border border-gray-200 dark:border-white/20 shadow-lg animate-slide-up">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-3">
                    @if ($type === 'level')
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-500/20">
                            <i class="fas fa-trophy text-xl text-yellow-500 dark:text-yellow-400 group-hover:animate-bounce"></i>
                        </div>
                    @elseif ($type === 'habits_completed')
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-500/20">
                            <i class="fas fa-tasks text-xl text-emerald-500 dark:text-emerald-400 group-hover:animate-bounce"></i>
                        </div>
                    @elseif ($type === 'streak')
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 dark:bg-red-500/20">
                            <i class="fas fa-fire-alt text-xl text-red-500 dark:text-red-400 group-hover:animate-bounce"></i>
                        </div>
                    @else
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20">
                            <i class="fas fa-medal text-xl text-purple-500 dark:text-purple-400 group-hover:animate-bounce"></i>
                        </div>
                    @endif
                    {{ $category['title'] }}
                </h3>
                <p class="text-gray-600 dark:text-white/70 mb-6">{{ $category['description'] }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($category['achievements'] as $achievement)
                        @php
                            $isUnlocked = $user->hasAchievement($achievement);
                            $progress = $user->getAchievementProgress($achievement);
                            $percentage = $achievement->getProgressPercentageFor($user);
                        @endphp
                        
                        <div class="relative group achievement-card" data-achievement-id="{{ $achievement->id }}">
                            <div class="absolute inset-0 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <div class="relative p-6 rounded-xl {{ $isUnlocked ? 'bg-gray-100 dark:bg-white/20' : 'bg-white dark:bg-white/5' }} backdrop-blur-md border border-gray-200 dark:border-white/20 shadow-md transition duration-300 group-hover:border-gray-300 dark:group-hover:border-white/40">
                                <!-- Icono y Título -->
                                <div class="flex items-center mb-4">
                                    <div class="relative w-12 h-12 flex items-center justify-center rounded-lg mr-3 overflow-hidden bg-gradient-to-br from-motiveo-primary/10 to-motiveo-secondary/10 dark:from-motiveo-primary/20 dark:to-motiveo-secondary/20 group-hover:from-motiveo-primary/20 group-hover:to-motiveo-secondary/20 dark:group-hover:from-motiveo-primary/30 dark:group-hover:to-motiveo-secondary/30 transition-all duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-br from-motiveo-primary to-motiveo-secondary opacity-0 group-hover:opacity-10 dark:group-hover:opacity-20 transition-opacity duration-300"></div>
                                        <i class="fas fa-{{ $achievement->icon ?? 'trophy' }} text-2xl text-motiveo-primary dark:text-motiveo-accent group-hover:scale-125 group-hover:rotate-12 transition-all duration-300"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-white group-hover:text-motiveo-accent transition duration-300">
                                            {{ $achievement->name }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-white/70 text-sm">
                                            {{ $achievement->description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Barra de Progreso -->
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-2.5 shadow-inner overflow-hidden">
                                        <div class="progress-bar h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-motiveo-primary to-motiveo-secondary relative"
                                             style="width: {{ $percentage }}%">
                                            <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-xs mt-2">
                                        <span class="progress-text text-gray-600 dark:text-white/70 flex items-center gap-1">
                                            <i class="fas fa-bullseye text-motiveo-primary/70 dark:text-motiveo-accent/70"></i>
                                            {{ $progress }} / {{ $achievement->requirement }}
                                        </span>
                                        <span class="percentage-text text-gray-600 dark:text-white/70 flex items-center gap-1">
                                            <i class="fas fa-chart-pie text-motiveo-secondary/70 dark:text-motiveo-secondary/70"></i>
                                            {{ $percentage }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="achievement-status flex items-center gap-2 {{ $isUnlocked ? 'text-motiveo-success' : 'text-gray-500 dark:text-white/50' }}">
                                        @if($isUnlocked)
                                            <div class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 dark:bg-green-500/20">
                                                <i class="fas fa-check-circle text-green-500 dark:text-green-400"></i>
                                            </div>
                                            <span class="text-xs flex items-center gap-1">
                                                <i class="fas fa-unlock-alt"></i>
                                                Desbloqueado
                                            </span>
                                        @else
                                            <div class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-500/20">
                                                <i class="fas fa-lock text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                            <span class="text-xs flex items-center gap-1">
                                                <i class="fas fa-clock"></i>
                                                Pendiente
                                            </span>
                                        @endif
                                    </span>
                                    @if($isUnlocked)
                                        @php
                                            $unlockDate = $user->getAchievementUnlockDate($achievement);
                                        @endphp
                                        @if($unlockDate)
                                            <div class="text-xs text-green-500 dark:text-green-400 flex items-center gap-1">
                                                <i class="fas fa-calendar-check"></i>
                                                {{ $unlockDate->format('d M Y') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

        <!-- Initialize user stats from server -->
        <script>
            // Set initial user stats from server when page loads
            document.addEventListener('alpine:init', () => {
                const initialStats = {
                    xp: {{ auth()->user()->xp ?? 0 }},
                    level: {{ auth()->user()->level ?? 1 }},
                    progress: {{ auth()->user()->getLevelProgress() ?? 0 }},
                    next_level_xp: {{ auth()->user()->getXpForNextLevel() ?? 100 }}
                };
                
                Alpine.store('userStats', initialStats);
            });
        </script>

        <!-- Achievement Scripts -->
        <script>
            let achievementsData = {};
            let updateInterval;
            let isAutoUpdatePaused = false;        // Animaciones
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-slide-up');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 100}ms`;
            });

            // Inicializar datos de logros
            initializeAchievements();
            
            // Actualizar progreso inicial
            updateAchievementsProgress();
            
            // Configurar actualización automática cada 5 segundos
            updateInterval = setInterval(updateAchievementsProgress, 5000);
        });

        // Inicializar datos de logros
        function initializeAchievements() {
            document.querySelectorAll('.achievement-card').forEach(card => {
                const achievementId = card.dataset.achievementId;
                const progressElement = card.querySelector('.progress-bar');
                const progressText = card.querySelector('.progress-text');
                const statusElement = card.querySelector('.achievement-status');
                
                if (achievementId) {
                    achievementsData[achievementId] = {
                        element: card,
                        progressBar: progressElement,
                        progressText: progressText,
                        statusElement: statusElement,
                        currentProgress: parseInt(progressText.textContent.split('/')[0]) || 0,
                        requirement: parseInt(progressText.textContent.split('/')[1]) || 1,
                        isUnlocked: statusElement.textContent.includes('Desbloqueado')
                    };
                }
            });
        }

        // Actualizar progreso de logros
        async function updateAchievementsProgress() {
            const updateIcon = document.getElementById('update-icon');
            const statusIcon = document.getElementById('update-status-icon');
            
            // Mostrar indicador de carga
            if (updateIcon) {
                updateIcon.classList.add('fa-spin');
            }
            if (statusIcon) {
                statusIcon.className = 'w-2 h-2 bg-yellow-400 rounded-full animate-pulse';
            }
            
            try {
                const response = await fetch('{{ route("achievements.check") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Actualizar datos individuales de cada logro
                    await updateIndividualAchievements();
                    
                    // Indicador de éxito
                    if (statusIcon) {
                        statusIcon.className = 'w-2 h-2 bg-green-400 rounded-full animate-pulse';
                    }
                }
            } catch (error) {
                console.error('Error al actualizar logros:', error);
                
                // Indicador de error
                if (statusIcon) {
                    statusIcon.className = 'w-2 h-2 bg-red-400 rounded-full animate-pulse';
                }
            } finally {
                // Quitar indicador de carga
                if (updateIcon) {
                    updateIcon.classList.remove('fa-spin');
                }
            }
        }

        // Actualizar logros individuales
        async function updateIndividualAchievements() {
            for (const achievementId in achievementsData) {
                try {
                    const response = await fetch(`/achievements/${achievementId}/progress`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateAchievementDisplay(achievementId, data);
                    }
                } catch (error) {
                    console.error(`Error al actualizar logro ${achievementId}:`, error);
                }
            }
        }

        // Actualizar la visualización de un logro específico
        function updateAchievementDisplay(achievementId, data) {
            const achievement = achievementsData[achievementId];
            if (!achievement) return;

            const oldProgress = achievement.currentProgress;
            const newProgress = data.progress;
            const percentage = data.percentage;
            const isNowUnlocked = data.is_unlocked;

            // Verificar si hubo cambios
            if (oldProgress !== newProgress || achievement.isUnlocked !== isNowUnlocked) {
                // Añadir indicador de actualización
                const updateIndicator = document.createElement('div');
                updateIndicator.className = 'update-indicator';
                achievement.element.appendChild(updateIndicator);
                
                // Actualizar barra de progreso con animación
                if (achievement.progressBar) {
                    achievement.progressBar.style.transition = 'width 0.7s ease-out';
                    achievement.progressBar.style.width = `${percentage}%`;
                }

                // Actualizar texto de progreso
                if (achievement.progressText) {
                    achievement.progressText.textContent = `${newProgress} / ${data.requirement}`;
                }

                // Actualizar porcentaje
                const percentageElement = achievement.element.querySelector('.percentage-text');
                if (percentageElement) {
                    percentageElement.textContent = `${percentage}%`;
                }

                // Actualizar estado de desbloqueo
                if (achievement.statusElement && achievement.isUnlocked !== isNowUnlocked) {
                    if (isNowUnlocked) {
                        achievement.statusElement.innerHTML = '<i class="fas fa-unlock mr-2"></i>Desbloqueado';
                        achievement.statusElement.className = 'achievement-status text-motiveo-success';
                        achievement.element.querySelector('.relative').classList.remove('bg-white/5');
                        achievement.element.querySelector('.relative').classList.add('bg-white/20');
                        achievement.element.classList.add('achievement-unlocked');
                        
                        // Efecto de celebración
                        showAchievementUnlocked(data.achievement);
                    } else {
                        achievement.statusElement.innerHTML = '<i class="fas fa-lock mr-2"></i>Bloqueado';
                        achievement.statusElement.className = 'achievement-status text-white/50';
                        achievement.element.querySelector('.relative').classList.remove('bg-white/20');
                        achievement.element.querySelector('.relative').classList.add('bg-white/5');
                    }
                }

                // Actualizar datos locales
                achievement.currentProgress = newProgress;
                achievement.isUnlocked = isNowUnlocked;

                // Efecto visual de actualización
                achievement.element.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    achievement.element.style.transform = 'scale(1)';
                    achievement.element.classList.remove('achievement-unlocked');
                    
                    // Eliminar indicador de actualización
                    if (updateIndicator.parentNode) {
                        updateIndicator.parentNode.removeChild(updateIndicator);
                    }
                }, 1000);
            }
        }

        // Mostrar celebración de logro desbloqueado
        function showAchievementUnlocked(achievementName) {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-6 py-4 rounded-xl shadow-2xl z-50 transform translate-x-full transition-transform duration-500';
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="text-2xl">🏆</div>
                    <div>
                        <div class="font-bold">¡Logro Desbloqueado!</div>
                        <div class="text-sm opacity-90">${achievementName}</div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animar entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Animar salida y eliminar
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 4000);

            // Sonido de celebración (si está disponible)
            if (typeof playAchievementSound === 'function') {
                playAchievementSound();
            }
        }

        // Pausar/reanudar actualización automática
        function toggleAutoUpdate() {
            const pauseIcon = document.getElementById('pause-icon');
            const statusIcon = document.getElementById('update-status-icon');
            
            if (isAutoUpdatePaused) {
                // Reanudar
                updateInterval = setInterval(updateAchievementsProgress, 5000);
                pauseIcon.className = 'fas fa-pause';
                statusIcon.className = 'w-2 h-2 bg-green-400 rounded-full animate-pulse';
                isAutoUpdatePaused = false;
            } else {
                // Pausar
                if (updateInterval) {
                    clearInterval(updateInterval);
                }
                pauseIcon.className = 'fas fa-play';
                statusIcon.className = 'w-2 h-2 bg-gray-400 rounded-full';
                isAutoUpdatePaused = true;
            }
        }

        // Sonidos (si están habilitados)
        const achievementCards = document.querySelectorAll('.achievement-card');
        achievementCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                if (typeof playHoverSound === 'function') {
                    playHoverSound();
                }
            });
        });

        // Limpiar intervalo al salir de la página
        window.addEventListener('beforeunload', () => {
            if (updateInterval) {
                clearInterval(updateInterval);
            }
        });
    </script>
</body>
</html>