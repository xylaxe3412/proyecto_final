<!-- Control de Música de Fondo -->
<div class="fixed bottom-6 left-6 z-50" x-data="{ showMusicControls: false, musicInfo: { isEnabled: true, isPlaying: false, volume: 0.3, trackName: 'Balatro Main Theme', currentTime: '0:00', duration: '0:00', progress: 0 } }" 
     x-init="
        // Actualizar info de música cada segundo
        setInterval(() => {
            if (window.MusicManager) {
                musicInfo = window.MusicManager.getControls();
            }
        }, 1000);
     ">
    
    <!-- Botón flotante principal -->
    <div class="relative">
        <!-- Indicador visual de música reproduciéndose -->
        <div x-show="musicInfo.isPlaying" 
             class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse z-10"></div>
        
        <!-- Botón principal -->
        <button 
            @click="showMusicControls = !showMusicControls"
            class="w-14 h-14 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 transition-all duration-300 group relative overflow-hidden"
            :class="{ 'ring-4 ring-indigo-400/50': showMusicControls, 'animate-bounce': musicInfo.isPlaying && !showMusicControls }"
        >
            <!-- Icono dinámico -->
            <i :class="musicInfo.isPlaying ? 'fas fa-music animate-pulse' : 'fas fa-music'" 
               class="text-lg group-hover:animate-bounce"></i>
            
            <!-- Efecto de ondas cuando reproduce -->
            <div x-show="musicInfo.isPlaying" 
                 class="absolute inset-0 rounded-full bg-white/20 animate-ping"></div>
        </button>

        <!-- Panel de controles expandido -->
        <div 
            x-show="showMusicControls"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 transform translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="showMusicControls = false"
            class="absolute bottom-16 left-0 bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-gray-200 dark:border-white/20 min-w-[350px]"
        >
            <!-- Header del panel -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-music text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-800 dark:text-white font-semibold text-lg">Música de Fondo</h3>
                        <p class="text-gray-600 dark:text-white/60 text-sm" x-text="musicInfo.trackName"></p>
                    </div>
                </div>
                <button 
                    @click="showMusicControls = false"
                    class="text-gray-600 hover:text-gray-800 dark:text-white/60 dark:hover:text-white transition-colors p-1"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progreso de la canción -->
            <div class="mb-6">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-white/60 mb-2">
                    <span x-text="musicInfo.currentTime"></span>
                    <span x-text="musicInfo.duration"></span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-300 relative"
                         :style="`width: ${musicInfo.progress}%`">
                        <div class="absolute right-0 top-0 w-2 h-2 bg-white rounded-full shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Controles principales -->
            <div class="flex items-center justify-center space-x-4 mb-6">
                <!-- Reiniciar -->
                <button 
                    @click="window.MusicManager && window.MusicManager.restart()"
                    class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all hover:scale-110"
                >
                    <i class="fas fa-redo text-sm"></i>
                </button>
                
                <!-- Play/Pause principal -->
                <button 
                    @click="window.MusicManager && window.MusicManager.toggle()"
                    class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 rounded-full flex items-center justify-center text-white transition-all hover:scale-110 shadow-lg"
                >
                    <i :class="musicInfo.isPlaying ? 'fas fa-pause' : 'fas fa-play'" class="text-lg"></i>
                </button>
                
                <!-- Información -->
                <button 
                    class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all hover:scale-110"
                    title="Tema de Balatro - Música relajante para tu productividad"
                >
                    <i class="fas fa-info text-sm"></i>
                </button>
            </div>

            <!-- Toggle activación -->
            <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-power-off mr-3 text-green-400"></i>
                    <span class="text-gray-800 dark:text-white font-medium">Música Activada</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        class="sr-only peer" 
                        :checked="musicInfo.isEnabled"
                        @change="window.MusicManager && window.MusicManager.toggleEnabled()"
                    >
                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-600 peer-checked:to-purple-600"></div>
                </label>
            </div>

            <!-- Control de volumen -->
            <div class="mb-4" x-show="musicInfo.isEnabled">
                <label class="block text-gray-700 dark:text-white/80 text-sm font-medium mb-3">
                    <i class="fas fa-volume-up mr-2"></i>
                    Volumen: <span x-text="Math.round(musicInfo.volume * 100)"></span>%
                </label>
                <input 
                    type="range" 
                    min="0" 
                    max="1" 
                    step="0.05"
                    :value="musicInfo.volume"
                    @input="window.MusicManager && window.MusicManager.setVolume(parseFloat($event.target.value))"
                    class="w-full h-2 bg-gray-200 dark:bg-white/20 rounded-lg appearance-none cursor-pointer music-slider"
                >
            </div>

            <!-- Estado -->
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-2 text-sm">
                    <div :class="musicInfo.isPlaying ? 'bg-green-500' : 'bg-gray-500'" 
                         class="w-2 h-2 rounded-full"></div>
                    <span class="text-gray-600 dark:text-white/60" 
                          x-text="musicInfo.isPlaying ? 'Reproduciendo' : (musicInfo.isEnabled ? 'Pausado' : 'Desactivado')"></span>
                </div>
            </div>

            <!-- Info motivacional -->
            <div class="mt-4 p-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-500/10 dark:to-purple-500/10 rounded-lg border border-indigo-200 dark:border-indigo-500/20">
                <p class="text-gray-700 dark:text-white/70 text-xs text-center flex items-center justify-center">
                    <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                    La música de fondo mejora tu concentración y hace más placentero construir hábitos
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para el slider de música -->
<style>
.music-slider::-webkit-slider-thumb {
    appearance: none;
    width: 18px;
    height: 18px;
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.music-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
    box-shadow: 0 0 15px rgba(79, 70, 229, 0.6);
}

.music-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
    border-radius: 50%;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.music-slider::-moz-range-thumb:hover {
    transform: scale(1.2);
    box-shadow: 0 0 15px rgba(79, 70, 229, 0.6);
}
</style>
