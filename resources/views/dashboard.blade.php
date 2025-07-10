<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Motiveo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-motiveo-dark via-purple-900 to-indigo-900 font-display">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-lg font-black text-white">M</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">MOTIVEO</h1>
                </div>

                <!-- User Level -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-3">
                        <div class="bg-motiveo-warning text-white px-3 py-1 rounded-full text-sm font-bold">
                            NIVEL 5
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-white text-sm">850/1000 XP</span>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-motiveo-pink to-red-400 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">U</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white/80 hover:text-white text-sm">Salir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Panel - Mis Hábitos -->
            <div class="space-y-6">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-warning rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">🏆</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Mis Hábitos</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Habit Item -->
                        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-lg">🏥</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold">Salud</h3>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="text-motiveo-success">🔥 7 días</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-lg">🏢</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold">Productividad</h3>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="text-motiveo-success">🔥 12 días</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-lg">😊</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold">Bienestar</h3>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="text-motiveo-warning">⭐ 45 días</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-lg">📚</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold">Aprendizaje</h3>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="text-motiveo-success">🔥 15 días</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="w-full mt-6 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white py-3 px-4 rounded-xl font-semibold hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300" onclick="createNewHabit()">
                        ➕ Crear Nuevo Hábito
                    </button>
                </div>
            </div>

            <!-- Center Panel - Actividades -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Meta Flash Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-motiveo-accent rounded-full flex items-center justify-center">
                                    <span class="text-white text-lg">🎯</span>
                                </div>
                                <h3 class="text-white font-semibold">Meta Flash</h3>
                            </div>
                            <div class="w-6 h-6 bg-motiveo-warning rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">🍯</span>
                            </div>
                        </div>
                        <p class="text-white/80 text-sm mb-4">Completar 3 tareas pendientes importantes del día</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-motiveo-success text-sm">🔥 5 días</span>
                            </div>
                            <button class="bg-motiveo-warning text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-motiveo-warning/80 transition-all" onclick="completeActivity(this, 'Meta Flash')">
                                Completar 2/3
                            </button>
                        </div>
                    </div>

                    <!-- Hidratación Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-motiveo-accent rounded-full flex items-center justify-center">
                                    <span class="text-white text-lg">💧</span>
                                </div>
                                <h3 class="text-white font-semibold">Hidratación Express</h3>
                            </div>
                        </div>
                        <p class="text-white/80 text-sm mb-4">Tomar 1 vaso de agua ahora y mantenerte hidratado</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-motiveo-warning text-sm">⭐ 28 días</span>
                            </div>
                            <button class="bg-motiveo-success text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-motiveo-success/80 transition-all" onclick="completeActivity(this, 'Hidratación Express')">
                                Tomar agua 5/6
                            </button>
                        </div>
                    </div>

                    <!-- Desestrés Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-motiveo-pink rounded-full flex items-center justify-center">
                                    <span class="text-white text-lg">🧘</span>
                                </div>
                                <h3 class="text-white font-semibold">Desestrés Rápido</h3>
                            </div>
                        </div>
                        <p class="text-white/80 text-sm mb-4">Respiración 4-7-8 durante 2 minutos para relajarte</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-motiveo-success text-sm">🔥 9 días</span>
                            </div>
                            <button class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-400 transition-all" onclick="completeActivity(this, 'Desestrés Rápido')">
                                Completar 0/5
                            </button>
                        </div>
                    </div>

                    <!-- Micro-aprendizaje Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-motiveo-secondary rounded-full flex items-center justify-center">
                                    <span class="text-white text-lg">📚</span>
                                </div>
                                <h3 class="text-white font-semibold">Micro-aprendizaje</h3>
                            </div>
                        </div>
                        <p class="text-white/80 text-sm mb-4">Leer un artículo profesional de tu área de interés</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-motiveo-success text-sm">🔥 16 días</span>
                            </div>
                            <button class="bg-motiveo-success text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-motiveo-success/80 transition-all" onclick="completeActivity(this, 'Micro-aprendizaje')">
                                Leer 3/7
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Completados y Progreso -->
            <div class="space-y-6">
                <!-- Completados Hoy -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">✅</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Completados Hoy</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="text-motiveo-accent text-sm font-medium">08:15</div>
                                <div class="text-white text-sm">🧘 Meditación (Salud)</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="text-motiveo-accent text-sm font-medium">10:30</div>
                                <div class="text-white text-sm">🏢 Revisión metas (Productividad)</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="text-motiveo-accent text-sm font-medium">13:20</div>
                                <div class="text-white text-sm">💧 Hidratación (Bienestar)</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="text-motiveo-accent text-sm font-medium">16:45</div>
                                <div class="text-white text-sm">🌍 Inglés (Aprendizaje)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progreso por Categoría -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">📊</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Progreso por Categoría</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-red-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm">🏥</span>
                                </div>
                                <span class="text-white text-sm">Salud</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-24 h-2 bg-white/20 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-pink-500 rounded-full" style="width: 90%"></div>
                                </div>
                                <span class="text-white text-sm font-medium">90%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm">🏢</span>
                                </div>
                                <span class="text-white text-sm">Productividad</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-24 h-2 bg-white/20 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="text-white text-sm font-medium">75%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm">😊</span>
                                </div>
                                <span class="text-white text-sm">Bienestar</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-24 h-2 bg-white/20 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width: 95%"></div>
                                </div>
                                <span class="text-white text-sm font-medium">95%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm">📚</span>
                                </div>
                                <span class="text-white text-sm">Aprendizaje</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-24 h-2 bg-white/20 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full" style="width: 60%"></div>
                                </div>
                                <span class="text-white text-sm font-medium">60%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <div class="mt-6 pt-6 border-t border-white/20">
                        <h3 class="text-white font-semibold mb-4">🏆 Próximos Logros:</h3>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3 p-2 bg-white/5 rounded-lg">
                                <span class="text-lg">👑</span>
                                <span class="text-white text-sm">Rey Hidratación (50 días) - 95%</span>
                            </div>
                            <div class="flex items-center space-x-3 p-2 bg-white/5 rounded-lg">
                                <span class="text-lg">🌙</span>
                                <span class="text-white text-sm">Sabio Nocturno (21 días) - 33%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript para interactividad
        function completeActivity(button, activityName) {
            // Simular completar actividad
            button.innerHTML = '✅ Completado';
            button.style.backgroundColor = '#10b981';
            button.disabled = true;
            
            // Mostrar notificación
            showNotification(`¡${activityName} completado!`);
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-motiveo-success text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function createNewHabit() {
            showNotification('¡Funcionalidad de crear hábito próximamente!');
        }
    </script>
</body>
</html>
