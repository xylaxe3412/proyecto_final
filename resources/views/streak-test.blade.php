<!DOCTYPE html>
<html>
<head>
    <title>Prueba de Notificaciones de Racha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-white p-8" x-data="streakTest()">
    <h1 class="text-3xl font-bold mb-8">🧪 Prueba de Notificaciones de Racha</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Controles -->
        <div class="bg-gray-800 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Controles de Prueba</h2>
            <div class="space-y-3">
                <button @click="testWarning()" 
                        class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded text-white">
                    ⚠️ Advertencia de Racha
                </button>
                <button @click="testStarted()" 
                        class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded text-white">
                    🚀 Racha Iniciada
                </button>
                <button @click="testSaved()" 
                        class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 rounded text-white">
                    🔥 Racha Salvada
                </button>
                <button @click="testRecord()" 
                        class="w-full px-4 py-2 bg-purple-500 hover:bg-purple-600 rounded text-white">
                    🏆 Nuevo Récord
                </button>
                <button @click="loadData()" 
                        class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 rounded text-white">
                    🔄 Cargar Datos de API
                </button>
            </div>
        </div>
        
        <!-- Estado -->
        <div class="bg-gray-800 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Estado Actual</h2>
            <div class="space-y-2 text-sm">
                <div>Racha Actual: <span class="text-yellow-400" x-text="streakData.current"></span></div>
                <div>Mejor Racha: <span class="text-green-400" x-text="streakData.best"></span></div>
                <div>Horas Restantes: <span class="text-orange-400" x-text="streakData.hoursUntilReset"></span></div>
                <div>Estado API: <span class="text-blue-400" x-text="apiStatus"></span></div>
            </div>
        </div>
    </div>
    
    <!-- Logs -->
    <div class="mt-8 bg-gray-800 p-6 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">📋 Logs</h2>
        <div class="bg-black p-4 rounded font-mono text-sm max-h-64 overflow-y-auto">
            <template x-for="log in logs" :key="log.id">
                <div x-text="log.message" :class="log.type === 'error' ? 'text-red-400' : 'text-green-400'"></div>
            </template>
        </div>
    </div>

    <!-- Notificaciones de Racha -->
    <div id="streak-warning" 
         x-show="streakNotifications.warning.show"
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         class="fixed top-20 right-4 bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
                border-2 border-yellow-300/50 backdrop-blur-sm max-w-sm">
        <div class="flex items-center space-x-3">
            <div class="text-2xl animate-pulse">
                <i class="fas fa-fire text-yellow-300"></i>
            </div>
            <div>
                <div class="font-bold text-lg">¡Racha en Peligro! 🔥</div>
                <div class="text-sm opacity-90" x-text="streakNotifications.warning.message"></div>
                <div class="text-xs opacity-75 mt-1">Completa un hábito para mantenerla</div>
            </div>
            <button @click="closeNotification('warning')" 
                    class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
                ×
            </button>
        </div>
    </div>

    <div id="streak-started" 
         x-show="streakNotifications.started.show"
         x-transition:enter="transition ease-out duration-600 transform"
         x-transition:enter-start="opacity-0 transform translate-y-full scale-90 rotate-2"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100 rotate-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100 rotate-0"
         x-transition:leave-end="opacity-0 transform translate-y-full scale-90 rotate-2"
         class="fixed top-36 right-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
                border-2 border-blue-300/50 backdrop-blur-sm max-w-sm animate-bounce">
        <div class="flex items-center space-x-3">
            <div class="text-2xl animate-pulse">
                <i class="fas fa-rocket text-yellow-300"></i>
            </div>
            <div>
                <div class="font-bold text-lg">¡Racha Iniciada! 🚀</div>
                <div class="text-sm opacity-90" x-text="streakNotifications.started.message"></div>
                <div class="text-xs opacity-75 mt-1">¡Comienza tu jornada diaria!</div>
            </div>
            <button @click="closeNotification('started')" 
                    class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
                ×
            </button>
        </div>
    </div>

    <div id="streak-saved" 
         x-show="streakNotifications.saved.show"
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
         class="fixed top-36 right-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
                border-2 border-green-300/50 backdrop-blur-sm max-w-sm">
        <div class="flex items-center space-x-3">
            <div class="text-2xl animate-bounce">
                <i class="fas fa-fire text-yellow-300"></i>
            </div>
            <div>
                <div class="font-bold text-lg">¡Racha Salvada! 🎉</div>
                <div class="text-sm opacity-90" x-text="streakNotifications.saved.message"></div>
                <div class="text-xs opacity-75 mt-1">¡Sigue así!</div>
            </div>
            <button @click="closeNotification('saved')" 
                    class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
                ×
            </button>
        </div>
    </div>

    <div id="streak-record" 
         x-show="streakNotifications.record.show"
         x-transition:enter="transition ease-out duration-700 transform"
         x-transition:enter-start="opacity-0 transform translate-x-full scale-90 rotate-3"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100 rotate-0"
         x-transition:leave="transition ease-in duration-400 transform"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100 rotate-0"
         x-transition:leave-end="opacity-0 transform translate-x-full scale-90 rotate-3"
         class="fixed bottom-4 right-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
                border-2 border-purple-300/50 backdrop-blur-sm max-w-sm animate-pulse">
        <div class="flex items-center space-x-3">
            <div class="text-3xl animate-spin">
                <i class="fas fa-crown text-yellow-300"></i>
            </div>
            <div>
                <div class="font-bold text-lg">¡Nuevo Récord! 👑</div>
                <div class="text-sm opacity-90" x-text="streakNotifications.record.message"></div>
                <div class="text-xs opacity-75 mt-1">¡Increíble dedicación!</div>
            </div>
            <button @click="closeNotification('record')" 
                    class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
                ×
            </button>
        </div>
    </div>

    <script>
        function streakTest() {
            return {
                streakNotifications: {
                    warning: { show: false, message: '', days: 0 },
                    started: { show: false, message: '', days: 0 },
                    saved: { show: false, message: '', days: 0 },
                    record: { show: false, message: '', days: 0 }
                },
                streakData: {
                    current: 0,
                    best: 0,
                    lastCompleted: null,
                    hoursUntilReset: 0
                },
                apiStatus: 'No cargado',
                logs: [],
                logId: 0,

                init() {
                    this.addLog('🚀 Sistema iniciado');
                    this.loadData();
                },

                addLog(message, type = 'info') {
                    this.logs.unshift({
                        id: this.logId++,
                        message: `[${new Date().toLocaleTimeString()}] ${message}`,
                        type: type
                    });
                    if (this.logs.length > 20) this.logs.pop();
                },

                async loadData() {
                    try {
                        this.addLog('🔄 Cargando datos de API...');
                        const response = await fetch('/api/user-streak');
                        if (response.ok) {
                            const data = await response.json();
                            this.streakData = {
                                current: data.current_streak || 0,
                                best: data.best_streak || 0,
                                lastCompleted: data.last_completed,
                                hoursUntilReset: data.hours_until_reset || 0
                            };
                            this.apiStatus = 'Cargado exitosamente';
                            this.addLog(`✅ Datos cargados: ${this.streakData.current} días, ${this.streakData.hoursUntilReset}h restantes`);
                        } else {
                            this.apiStatus = `Error ${response.status}`;
                            this.addLog(`❌ Error API: ${response.status}`, 'error');
                        }
                    } catch (error) {
                        this.apiStatus = 'Error de conexión';
                        this.addLog(`❌ Error: ${error.message}`, 'error');
                    }
                },

                testWarning() {
                    this.addLog('🧪 Probando advertencia de racha...');
                    this.showNotification('warning', '⚠️ ¡Tu racha de 15 días está en riesgo! Quedan menos de 2 horas', 15);
                },

                testStarted() {
                    this.addLog('🧪 Probando racha iniciada...');
                    this.showNotification('started', '🔥 ¡Racha iniciada! Comienza tu jornada de hábitos consecutivos', 1);
                },

                testSaved() {
                    this.addLog('🧪 Probando racha salvada...');
                    this.showNotification('saved', '🔥 ¡Racha salvada! Llevas 16 días consecutivos', 16);
                },

                testRecord() {
                    this.addLog('🧪 Probando nuevo récord...');
                    this.showNotification('record', '🏆 ¡Nuevo récord personal! 25 días consecutivos', 25);
                },

                showNotification(type, message, days) {
                    this.addLog(`📣 Mostrando notificación: ${type}`);
                    if (this.streakNotifications[type]) {
                        this.streakNotifications[type] = {
                            show: true,
                            message: message,
                            days: days
                        };

                        setTimeout(() => {
                            if (this.streakNotifications[type]) {
                                this.streakNotifications[type].show = false;
                                this.addLog(`⏰ Auto-ocultando notificación: ${type}`);
                            }
                        }, 8000);
                    }
                },

                closeNotification(type) {
                    this.addLog(`❌ Cerrando notificación: ${type}`);
                    if (this.streakNotifications[type]) {
                        this.streakNotifications[type].show = false;
                    }
                }
            }
        }
    </script>
</body>
</html>