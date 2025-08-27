<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sincronización de Hábitos - Motiveo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1f2937',
                        'secondary': '#374151',
                        'accent': '#3b82f6',
                        'success': '#10b981',
                        'danger': '#ef4444',
                        'warning': '#f59e0b',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="h-full bg-gray-50 font-sans" x-data="habitSyncApp()">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-sm font-bold text-white">M</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">Sincronización de Hábitos</h1>
                </div>
                <a href="/" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Loading State -->
        <div x-show="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-accent"></div>
            <p class="mt-2 text-gray-600">Verificando actualizaciones...</p>
        </div>

        <!-- Updates Available -->
        <div x-show="!loading && updates.length > 0" class="space-y-6">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Actualizaciones Disponibles</h2>
                        <p class="text-gray-600 mt-1" x-text="`${updates.length} hábito(s) tienen actualizaciones disponibles`"></p>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="syncAllHabits()" 
                                :disabled="syncing"
                                class="bg-accent text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors disabled:opacity-50">
                            <span x-show="!syncing">Actualizar Todo</span>
                            <span x-show="syncing">Sincronizando...</span>
                        </button>
                        <button @click="checkUpdates()" 
                                :disabled="loading"
                                class="bg-gray-100 text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                            Verificar Nuevamente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Updates List -->
            <div class="space-y-4">
                <template x-for="update in updates" :key="update.habit_id">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="update.habit_name"></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning text-white">
                                        Actualización disponible
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Plantilla</p>
                                        <p class="text-sm text-gray-600" x-text="update.template_name"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Versión Actual</p>
                                        <p class="text-sm text-gray-600" x-text="update.current_version"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Nueva Versión</p>
                                        <p class="text-sm text-accent font-semibold" x-text="update.latest_version"></p>
                                    </div>
                                </div>
                                
                                <div x-show="update.changelog" class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-1">Cambios:</p>
                                    <p class="text-sm text-gray-600" x-text="update.changelog"></p>
                                </div>
                                
                                <div x-show="update.last_synced" class="text-xs text-gray-500">
                                    Última sincronización: <span x-text="new Date(update.last_synced).toLocaleString()"></span>
                                </div>
                            </div>
                            
                            <div class="ml-6 flex flex-col space-y-2">
                                <button @click="syncHabit(update.habit_id, true)" 
                                        :disabled="syncing"
                                        class="bg-success text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors disabled:opacity-50">
                                    Actualizar (Preservar cambios)
                                </button>
                                <button @click="syncHabit(update.habit_id, false)" 
                                        :disabled="syncing"
                                        class="bg-warning text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition-colors disabled:opacity-50">
                                    Restablecer completamente
                                </button>
                                <button @click="showHistory(update.habit_id)" 
                                        class="bg-gray-100 text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                                    Ver Historial
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- No Updates -->
        <div x-show="!loading && updates.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-white text-2xl font-bold">✓</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Todo está actualizado</h2>
            <p class="text-gray-600 mb-8">
                Todos tus hábitos están sincronizados con las versiones más recientes.
            </p>
            <button @click="checkUpdates()" 
                    class="bg-accent text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                Verificar Nuevamente
            </button>
        </div>

        <!-- History Modal -->
        <div x-show="showHistoryModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Historial de Sincronización</h3>
                    <button @click="showHistoryModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div x-show="historyData">
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-900" x-text="historyData?.nombre"></h4>
                            <p class="text-sm text-gray-600" x-text="`ID de plantilla: ${historyData?.template_id}`"></p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Versión Actual</p>
                                <p class="text-sm text-gray-600" x-text="historyData?.template_version"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Sincronización Habilitada</p>
                                <p class="text-sm" :class="historyData?.sync_enabled ? 'text-success' : 'text-danger'" 
                                   x-text="historyData?.sync_enabled ? 'Sí' : 'No'"></p>
                            </div>
                        </div>
                        
                        <div x-show="historyData?.last_synced_at">
                            <p class="text-sm font-medium text-gray-700">Última Sincronización</p>
                            <p class="text-sm text-gray-600" x-text="historyData?.last_synced_at ? new Date(historyData.last_synced_at).toLocaleString() : 'Nunca'"></p>
                        </div>
                        
                        <div x-show="historyData?.sync_notes">
                            <p class="text-sm font-medium text-gray-700">Notas de Sincronización</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg" x-text="historyData?.sync_notes"></p>
                        </div>
                        
                        <div x-show="historyData?.custom_settings">
                            <p class="text-sm font-medium text-gray-700">Configuraciones Personalizadas</p>
                            <pre class="text-xs text-gray-600 bg-gray-50 p-3 rounded-lg overflow-x-auto" x-text="JSON.stringify(historyData?.custom_settings, null, 2)"></pre>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="toggleSync(historyData?.id)" 
                                class="bg-accent text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors">
                            <span x-text="historyData?.sync_enabled ? 'Deshabilitar Sync' : 'Habilitar Sync'"></span>
                        </button>
                        <button @click="showHistoryModal = false" 
                                class="bg-gray-100 text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div x-show="notification.show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-4 right-4 z-50">
            <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center"
                             :class="notification.type === 'success' ? 'bg-success' : notification.type === 'error' ? 'bg-danger' : 'bg-accent'">
                            <span class="text-white text-sm font-bold"
                                  x-text="notification.type === 'success' ? '✓' : notification.type === 'error' ? '✗' : 'i'"></span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
                    </div>
                    <button @click="notification.show = false" class="ml-4 text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Cerrar</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function habitSyncApp() {
            return {
                loading: true,
                syncing: false,
                updates: [],
                showHistoryModal: false,
                historyData: null,
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },

                init() {
                    this.checkUpdates();
                },

                async checkUpdates() {
                    this.loading = true;
                    try {
                        const response = await fetch('/habits/sync/check-updates');
                        const data = await response.json();
                        
                        if (data.success) {
                            this.updates = data.updates;
                            if (data.updates_available > 0) {
                                this.showNotification(`${data.updates_available} actualización(es) disponible(s)`, 'info');
                            }
                        }
                    } catch (error) {
                        console.error('Error checking updates:', error);
                        this.showNotification('Error al verificar actualizaciones', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async syncHabit(habitId, preserveCustomizations) {
                    this.syncing = true;
                    try {
                        const response = await fetch(`/habits/sync/${habitId}/sync`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                preserve_customizations: preserveCustomizations
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            // Remove the updated habit from the list
                            this.updates = this.updates.filter(update => update.habit_id !== habitId);
                        } else {
                            this.showNotification(data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error syncing habit:', error);
                        this.showNotification('Error al sincronizar hábito', 'error');
                    } finally {
                        this.syncing = false;
                    }
                },

                async syncAllHabits() {
                    this.syncing = true;
                    try {
                        const response = await fetch('/habits/sync/sync-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                preserve_customizations: true
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.updates = []; // Clear all updates
                            
                            if (data.errors.length > 0) {
                                console.warn('Some habits had errors:', data.errors);
                            }
                        } else {
                            this.showNotification('Error al sincronizar hábitos', 'error');
                        }
                    } catch (error) {
                        console.error('Error syncing all habits:', error);
                        this.showNotification('Error al sincronizar todos los hábitos', 'error');
                    } finally {
                        this.syncing = false;
                    }
                },

                async showHistory(habitId) {
                    try {
                        const response = await fetch(`/habits/sync/${habitId}/history`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.historyData = data.habit;
                            this.showHistoryModal = true;
                        } else {
                            this.showNotification('Error al cargar historial', 'error');
                        }
                    } catch (error) {
                        console.error('Error loading history:', error);
                        this.showNotification('Error al cargar historial', 'error');
                    }
                },

                async toggleSync(habitId) {
                    try {
                        const response = await fetch(`/habits/sync/${habitId}/toggle-sync`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.historyData.sync_enabled = data.sync_enabled;
                        } else {
                            this.showNotification('Error al cambiar configuración', 'error');
                        }
                    } catch (error) {
                        console.error('Error toggling sync:', error);
                        this.showNotification('Error al cambiar configuración', 'error');
                    }
                },

                showNotification(message, type = 'info') {
                    this.notification = {
                        show: true,
                        message: message,
                        type: type
                    };

                    // Auto-hide after 5 seconds
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 5000);
                }
            }
        }
    </script>
</body>
</html>
