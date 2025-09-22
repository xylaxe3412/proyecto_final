/**
 * Sound Effects Manager para Motiveo Dashboard
 * Sistema para efectos de sonido cortos (completar hábitos, subir nivel, etc.)
 */

class SoundEffectsManager {
    constructor() {
        console.log('🔊 Iniciando SoundEffectsManager...');
        
        // Configuración
        this.isEnabled = localStorage.getItem('sound_effects_enabled') !== 'false';
        this.volume = parseFloat(localStorage.getItem('sound_effects_volume')) || 0.4;
        this.sounds = {};
        
        // Definir mapeo de sonidos
        this.soundMap = {
            levelUp: 'Voicy_Level complete .mp3',
            lessonComplete: 'Voicy_Lesson complete.mp3',
            badAnswer: 'Voicy_Bad answer.mp3',
            livesLost: 'Voicy_Lives lost .mp3',
            sadTrombone: 'Voicy_Sad trombone.mp3',
            match: 'Voicy_Tinder match notification.mp3'
        };
        
        this.init();
    }

    /**
     * Inicializar el sistema de efectos
     */
    init() {
        // Precargar todos los sonidos
        Object.entries(this.soundMap).forEach(([key, filename]) => {
            this.loadSound(key, filename);
        });
        
        console.log(`✅ Sound Effects configurados: ${Object.keys(this.soundMap).length} sonidos`);
        console.log(`🎛️ Volumen: ${Math.round(this.volume * 100)}% | Activado: ${this.isEnabled}`);
    }

    /**
     * Cargar un sonido específico
     */
    loadSound(key, filename) {
        try {
            console.log(`🔄 Cargando sonido: ${key} -> /sounds/effects/${filename}`);
            const audio = new Audio();
            audio.src = '/sounds/effects/' + encodeURIComponent(filename);
            audio.preload = 'auto';
            audio.volume = this.volume;
            
            // Event listeners para debug
            audio.addEventListener('canplaythrough', () => {
                console.log(`✅ Sonido listo para reproducir: ${key} (${filename})`);
            });
            
            audio.addEventListener('loadeddata', () => {
                console.log(`📁 Datos de sonido cargados: ${key}`);
            });
            
            audio.addEventListener('error', (e) => {
                console.error(`❌ Error cargando sonido ${key} (${filename}):`, e);
                console.error(`📍 URL intentada: ${audio.src}`);
            });
            
            this.sounds[key] = {
                audio: audio,
                filename: filename,
                isLoaded: false
            };
            
        } catch (error) {
            console.error(`❌ Error inicializando sonido ${key}:`, error);
        }
    }

    /**
     * Reproducir un efecto de sonido
     */
    play(soundKey, options = {}) {
        if (!this.isEnabled) {
            console.log(`🔇 Efectos de sonido desactivados. No reproduciendo: ${soundKey}`);
            return;
        }

        const sound = this.sounds[soundKey];
        if (!sound) {
            console.warn(`❌ Sonido no encontrado: ${soundKey}. Disponibles:`, Object.keys(this.sounds));
            return;
        }

        try {
            // Crear una nueva instancia para permitir solapamiento
            const audioClone = sound.audio.cloneNode();
            audioClone.volume = (options.volume || 1) * this.volume;
            
            console.log(`🔊 Reproduciendo efecto: ${soundKey} (${sound.filename})`);
            
            const playPromise = audioClone.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log(`✅ Efecto reproducido: ${soundKey}`);
                    })
                    .catch(error => {
                        console.warn(`⚠️ Error reproduciendo ${soundKey}:`, error);
                    });
            }
            
        } catch (error) {
            console.error(`❌ Error al reproducir ${soundKey}:`, error);
        }
    }

    /**
     * Efectos específicos para acciones del dashboard
     */
    
    // Sonido épico para subir de nivel
    playLevelUp(level = null) {
        console.log(`🏆 ¡SUBIDA DE NIVEL! ${level ? `Nivel ${level}` : ''}`);
        this.play('levelUp', { volume: 1.2 });
        
        // Efecto visual adicional (opcional)
        setTimeout(() => {
            console.log('✨ Efecto de nivel completado');
        }, 1000);
    }
    
    // Sonido para completar hábito/lección
    playHabitComplete() {
        console.log('✅ Hábito completado');
        this.play('lessonComplete', { volume: 0.9 });
    }
    
    // Sonido para errores o fallos
    playError() {
        console.log('❌ Error o fallo');
        this.play('badAnswer', { volume: 0.7 });
    }
    
    // Sonido para perder vidas/fallos graves
    playLifeLost() {
        console.log('💔 Vida perdida');
        this.play('livesLost', { volume: 0.8 });
    }
    
    // Sonido cómico para fallos
    playSadTrombone() {
        console.log('😞 Fallo cómico');
        this.play('sadTrombone', { volume: 0.6 });
    }
    
    // Sonido de notificación positiva
    playMatch() {
        console.log('💫 Match/Notificación');
        this.play('match', { volume: 0.8 });
    }

    /**
     * Activar/desactivar efectos de sonido
     */
    toggleEnabled() {
        this.isEnabled = !this.isEnabled;
        localStorage.setItem('sound_effects_enabled', this.isEnabled.toString());
        
        console.log(`🔊 Efectos de sonido: ${this.isEnabled ? 'ACTIVADOS' : 'DESACTIVADOS'}`);
        
        // Reproducir confirmación si se activó
        if (this.isEnabled) {
            setTimeout(() => this.playMatch(), 200);
        }
        
        return this.isEnabled;
    }

    /**
     * Ajustar volumen de efectos
     */
    setVolume(volume) {
        this.volume = Math.max(0, Math.min(1, volume));
        localStorage.setItem('sound_effects_volume', this.volume.toString());
        
        // Actualizar volumen de todos los sonidos cargados
        Object.values(this.sounds).forEach(sound => {
            if (sound.audio) {
                sound.audio.volume = this.volume;
            }
        });
        
        console.log(`🎛️ Volumen de efectos ajustado: ${Math.round(this.volume * 100)}%`);
        
        // Reproducir sonido de prueba
        setTimeout(() => this.playMatch(), 100);
    }

    /**
     * Obtener información para controles UI
     */
    getControls() {
        return {
            isEnabled: this.isEnabled,
            volume: this.volume,
            availableSounds: Object.keys(this.sounds),
            
            // Métodos
            toggleEnabled: () => this.toggleEnabled(),
            setVolume: (vol) => this.setVolume(vol),
            playLevelUp: (level) => this.playLevelUp(level),
            playHabitComplete: () => this.playHabitComplete(),
            playError: () => this.playError(),
            playTest: () => this.playMatch()
        };
    }

    /**
     * Reproducir efecto contextual basado en acción
     */
    playContextual(action, context = {}) {
        console.log(`🎮 Acción contextual: ${action}`, context);
        
        switch(action) {
            case 'level.up':
                this.playLevelUp(context.level);
                break;
            case 'habit.complete':
                this.playHabitComplete();
                break;
            case 'habit.error':
            case 'error':
                this.playError();
                break;
            case 'life.lost':
                this.playLifeLost();
                break;
            case 'fail.comic':
                this.playSadTrombone();
                break;
            case 'notification':
            case 'match':
                this.playMatch();
                break;
            default:
                console.log(`⚠️ Acción contextual no reconocida: ${action}`);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar inmediatamente, sin delay
    window.SoundEffects = new SoundEffectsManager();
    console.log('🔊 SoundEffects disponible globalmente');
});

// Inicialización alternativa para asegurar disponibilidad
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSoundEffects);
} else {
    // DOM ya está listo
    initSoundEffects();
}

function initSoundEffects() {
    if (!window.SoundEffects) {
        window.SoundEffects = new SoundEffectsManager();
        console.log('🔊 SoundEffects inicializado (fallback)');
    }
}

// Exportar para uso en otros archivos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SoundEffectsManager;
}
