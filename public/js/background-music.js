/**
 * Background Music Manager para Motiveo Dashboard
 * Sistema especializado para música de fondo con tema de Balatro
 */

class BackgroundMusicManager {
    constructor() {
        console.log('🎵 Iniciando BackgroundMusicManager...');
        
        // Configuración
        this.isEnabled = localStorage.getItem('background_music_enabled') !== 'false';
        this.volume = parseFloat(localStorage.getItem('background_music_volume')) || 0.3;
        this.currentTrack = null;
        this.isPlaying = false;
        this.isLoaded = false;
        
        // Información del track
        this.trackInfo = {
            name: "Balatro Main Theme",
            file: "Balatro Main Theme.mp3",
            duration: null,
            currentTime: 0
        };
        
        this.init();
    }

    /**
     * Inicializar el sistema de música
     */
    init() {
        // Crear elemento de audio
        this.audio = new Audio();
        this.audio.src = '/sounds/music/' + encodeURIComponent(this.trackInfo.file);
        this.audio.loop = true;
        this.audio.volume = this.volume;
        this.audio.preload = 'auto';
        
        // Event listeners
        this.setupEventListeners();
        
        console.log(`✅ Música configurada: ${this.trackInfo.name}`);
        console.log(`🎛️ Volumen: ${Math.round(this.volume * 100)}% | Activada: ${this.isEnabled}`);
    }

    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Cuando la música está lista para reproducir
        this.audio.addEventListener('canplaythrough', () => {
            this.isLoaded = true;
            this.trackInfo.duration = this.audio.duration;
            console.log(`✅ Música cargada: ${this.trackInfo.name} (${this.formatTime(this.audio.duration)})`);
            
            // Auto-play si está habilitado
            if (this.isEnabled) {
                this.play();
            }
        });

        // Error al cargar
        this.audio.addEventListener('error', (e) => {
            console.error('❌ Error cargando música:', e);
            this.isLoaded = false;
        });

        // Actualizar progreso
        this.audio.addEventListener('timeupdate', () => {
            this.trackInfo.currentTime = this.audio.currentTime;
        });

        // Cuando termina (aunque está en loop)
        this.audio.addEventListener('ended', () => {
            console.log('🔄 Música terminada, reiniciando...');
            this.isPlaying = false;
        });

        // Pausar música cuando se cambia de pestaña
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.isPlaying) {
                this.pause();
                this.wasPlayingBeforeHide = true;
            } else if (!document.hidden && this.wasPlayingBeforeHide) {
                this.play();
                this.wasPlayingBeforeHide = false;
            }
        });
    }

    /**
     * Reproducir música
     */
    async play() {
        if (!this.isEnabled || !this.isLoaded) {
            console.log('🔇 Música desactivada o no cargada');
            return;
        }

        try {
            await this.audio.play();
            this.isPlaying = true;
            console.log('▶️ Reproduciendo:', this.trackInfo.name);
        } catch (error) {
            console.log('⚠️ Error reproduciendo música (posible política del navegador):', error);
        }
    }

    /**
     * Pausar música
     */
    pause() {
        if (this.audio && !this.audio.paused) {
            this.audio.pause();
            this.isPlaying = false;
            console.log('⏸️ Música pausada');
        }
    }

    /**
     * Alternar reproducción
     */
    toggle() {
        if (this.isPlaying) {
            this.pause();
        } else {
            this.play();
        }
        return this.isPlaying;
    }

    /**
     * Activar/desactivar música de fondo
     */
    toggleEnabled() {
        this.isEnabled = !this.isEnabled;
        localStorage.setItem('background_music_enabled', this.isEnabled.toString());
        
        if (this.isEnabled) {
            this.play();
        } else {
            this.pause();
        }
        
        console.log(`🎵 Música de fondo: ${this.isEnabled ? 'ACTIVADA' : 'DESACTIVADA'}`);
        return this.isEnabled;
    }

    /**
     * Ajustar volumen
     */
    setVolume(volume) {
        this.volume = Math.max(0, Math.min(1, volume));
        localStorage.setItem('background_music_volume', this.volume.toString());
        
        if (this.audio) {
            this.audio.volume = this.volume;
        }
        
        console.log(`🎛️ Volumen ajustado: ${Math.round(this.volume * 100)}%`);
    }

    /**
     * Obtener información actual
     */
    getInfo() {
        return {
            isEnabled: this.isEnabled,
            isPlaying: this.isPlaying,
            isLoaded: this.isLoaded,
            volume: this.volume,
            track: this.trackInfo,
            currentTime: this.audio ? this.audio.currentTime : 0,
            duration: this.audio ? this.audio.duration : 0
        };
    }

    /**
     * Formatear tiempo en mm:ss
     */
    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    /**
     * Reiniciar desde el principio
     */
    restart() {
        if (this.audio) {
            this.audio.currentTime = 0;
            if (this.isEnabled) {
                this.play();
            }
        }
    }

    /**
     * Obtener controles para la UI
     */
    getControls() {
        return {
            isEnabled: this.isEnabled,
            isPlaying: this.isPlaying,
            volume: this.volume,
            trackName: this.trackInfo.name,
            currentTime: this.formatTime(this.trackInfo.currentTime),
            duration: this.formatTime(this.trackInfo.duration),
            progress: this.trackInfo.duration ? (this.trackInfo.currentTime / this.trackInfo.duration) * 100 : 0,
            
            // Métodos
            toggle: () => this.toggle(),
            toggleEnabled: () => this.toggleEnabled(),
            setVolume: (vol) => this.setVolume(vol),
            restart: () => this.restart()
        };
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Pequeño delay para asegurar que todo esté cargado
    setTimeout(() => {
        window.MusicManager = new BackgroundMusicManager();
        console.log('🎵 MusicManager disponible globalmente');
    }, 500);
});

// Exportar para uso en otros archivos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BackgroundMusicManager;
}
