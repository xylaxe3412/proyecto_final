// Componente Interactivo de Hábitos
class HabitsManager {
    constructor() {
        this.apiBase = '/habits';
        this.expandedHabit = null;
        this.confirmingHabit = null;
        this.userStats = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCSRF();
        this.loadInitialData();
    }

    setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }

    setupEventListeners() {
        // Event delegation para las tarjetas de hábitos
        document.addEventListener('click', this.handleCardClick.bind(this));
        
        // Modales
        this.setupModalListeners();
        
        // Botones de navegación
        this.setupNavigationListeners();
        
        // Auto-refresh cada 5 minutos
        setInterval(() => this.refreshData(), 300000);
    }

    setupModalListeners() {
        const cancelBtn = document.getElementById('cancel-complete');
        const confirmBtn = document.getElementById('confirm-complete');
        const closeLevelUp = document.getElementById('close-levelup');

        if (cancelBtn) cancelBtn.addEventListener('click', () => this.hideConfirmModal());
        if (confirmBtn) confirmBtn.addEventListener('click', () => this.confirmCompleteHabit());
        if (closeLevelUp) closeLevelUp.addEventListener('click', () => this.hideLevelUpModal());

        // Cerrar modal al hacer click fuera
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.hideAllModals();
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideAllModals();
            }
        });
    }

    setupNavigationListeners() {
        // Botón crear nuevo hábito
        const createBtn = document.querySelector('[data-action="create-habit"]');
        if (createBtn) {
            createBtn.addEventListener('click', () => this.showCreateHabitForm());
        }

        // Botón explorar sugerencias
        const exploreBtn = document.querySelector('[data-action="explore-suggestions"]');
        if (exploreBtn) {
            exploreBtn.addEventListener('click', () => this.scrollToSuggestions());
        }
    }

    async loadInitialData() {
        try {
            const response = await axios.get(`${this.apiBase}/data`);
            this.updateUIWithData(response.data);
        } catch (error) {
            console.error('Error loading initial data:', error);
            this.showNotification('Error al cargar los datos', 'error');
        }
    }

    handleCardClick(event) {
        const habitCard = event.target.closest('.habit-card');
        if (!habitCard) return;

        const habitId = habitCard.dataset.habitId;
        const habitType = habitCard.dataset.habitType;

        // Prevenir propagación para botones específicos
        if (event.target.closest('.complete-btn')) {
            event.stopPropagation();
            this.showConfirmModal(habitId, habitType);
            return;
        }

        if (event.target.closest('.add-suggested-btn')) {
            event.stopPropagation();
            this.addSuggestedHabit(habitId);
            return;
        }

        if (event.target.closest('.stats-btn')) {
            event.stopPropagation();
            this.showHabitStats(habitId, habitType);
            return;
        }

        // Expandir/contraer tarjeta
        this.toggleHabitExpansion(habitCard, habitId, habitType);
    }

    toggleHabitExpansion(card, habitId, habitType) {
        const isExpanded = card.classList.contains('habit-expanded');
        
        // Contraer todas las tarjetas
        this.collapseAllCards();

        if (!isExpanded) {
            // Expandir esta tarjeta
            this.expandCard(card);
            this.expandedHabit = { id: habitId, type: habitType };
            
            // Scroll suave hacia la tarjeta expandida
            setTimeout(() => {
                card.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }, 100);
        } else {
            this.expandedHabit = null;
        }
    }

    collapseAllCards() {
        document.querySelectorAll('.habit-card').forEach(card => {
            card.classList.remove('habit-expanded');
            card.classList.add('habit-collapsed');
            
            const content = card.querySelector('.habit-expanded-content');
            if (content) {
                content.style.display = 'none';
            }
        });
    }

    expandCard(card) {
        card.classList.remove('habit-collapsed');
        card.classList.add('habit-expanded');
        
        const expandedContent = card.querySelector('.habit-expanded-content');
        if (expandedContent) {
            expandedContent.style.display = 'block';
            
            // Animar la aparición del contenido
            expandedContent.style.opacity = '0';
            expandedContent.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                expandedContent.style.transition = 'all 0.3s ease-out';
                expandedContent.style.opacity = '1';
                expandedContent.style.transform = 'translateY(0)';
            }, 50);
        }
    }

    showConfirmModal(habitId, habitType) {
        const habit = this.findHabitById(habitId, habitType);
        if (!habit) return;

        const modal = document.getElementById('confirm-modal');
        const habitName = document.getElementById('confirm-habit-name');
        
        if (habitName) habitName.textContent = habit.name || habit.nombre;
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.animation = 'fadeIn 0.3s ease-out';
        }
        
        this.confirmingHabit = { id: habitId, type: habitType };
    }

    hideConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
        this.confirmingHabit = null;
    }

    async confirmCompleteHabit() {
        if (!this.confirmingHabit) return;

        const completeBtn = document.getElementById('confirm-complete');
        if (completeBtn) {
            completeBtn.disabled = true;
            completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Completando...';
        }

        try {
            const response = await axios.post(`${this.apiBase}/${this.confirmingHabit.id}/complete`);
            const data = response.data;
            
            this.hideConfirmModal();
            
            if (data.success) {
                await this.handleSuccessfulCompletion(data);
            } else {
                this.showNotification(data.message, 'warning');
            }
        } catch (error) {
            console.error('Error completing habit:', error);
            this.showNotification('Error al completar el hábito', 'error');
        } finally {
            if (completeBtn) {
                completeBtn.disabled = false;
                completeBtn.innerHTML = '¡Completar! (+20 XP)';
            }
        }
    }

    async handleSuccessfulCompletion(data) {
        // Actualizar UI del hábito
        this.updateHabitUI(data.habit);
        
        // Actualizar estadísticas del usuario
        this.updateUserStats(data.user_stats);
        
        // Mostrar animación de XP
        this.showXPGain(data.xp_gained);
        
        // Mostrar notificación de éxito
        this.showNotification(data.message, 'success');
        
        // Si subió de nivel, mostrar modal especial
        if (data.leveled_up) {
            setTimeout(() => {
                this.showLevelUpModal(data.new_level);
            }, 1000);
        }
        
        // Efecto de confeti si es una racha especial
        if (data.habit.streak && data.habit.streak % 7 === 0) {
            this.showConfettiEffect();
        }
    }

    async addSuggestedHabit(suggestionId) {
        const addBtn = document.querySelector(`[data-habit-id="${suggestionId}"] .add-suggested-btn`);
        
        if (addBtn) {
            addBtn.disabled = true;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Agregando...';
        }

        try {
            const response = await axios.post(`${this.apiBase}/suggestions/${suggestionId}/add`, {
                duration_days: 30
            });
            
            const data = response.data;
            
            if (data.success) {
                // Remover de sugerencias
                const suggestionCard = document.querySelector(`[data-habit-id="${suggestionId}"][data-habit-type="suggested"]`);
                if (suggestionCard) {
                    suggestionCard.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => suggestionCard.remove(), 300);
                }
                
                // Recargar datos para mostrar el nuevo hábito
                await this.refreshData();
                
                // Mostrar notificación
                this.showNotification(data.message, 'success');
                this.showXPGain(10);
                
            } else {
                this.showNotification('Error al agregar el hábito', 'error');
            }
        } catch (error) {
            console.error('Error adding suggested habit:', error);
            this.showNotification('Error al agregar el hábito', 'error');
        } finally {
            if (addBtn) {
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fas fa-plus mr-1"></i>Agregar';
            }
        }
    }

    updateHabitUI(habitData) {
        const habitCard = document.querySelector(`[data-habit-id="${habitData.id}"][data-habit-type="user"]`);
        if (!habitCard) return;

        // Marcar como completado con animación
        habitCard.classList.add('completed-habit');
        habitCard.style.animation = 'completionPulse 0.6s ease-out';
        
        // Actualizar el botón
        const completeBtn = habitCard.querySelector('.complete-btn');
        if (completeBtn) {
            completeBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Completado';
            completeBtn.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-teal-500', 'hover:shadow-lg');
            completeBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            completeBtn.disabled = true;
        }

        // Actualizar progreso
        this.updateProgressBar(habitCard, habitData.progress_percentage);
        
        // Actualizar racha
        this.updateStreakDisplay(habitCard, habitData.streak);
    }

    updateProgressBar(habitCard, percentage) {
        const progressBar = habitCard.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.transition = 'width 0.8s ease-out';
            progressBar.style.width = `${percentage}%`;
            
            // Añadir brillo temporal
            progressBar.style.boxShadow = '0 0 10px rgba(59, 130, 246, 0.5)';
            setTimeout(() => {
                progressBar.style.boxShadow = '';
            }, 1000);
        }
    }

    updateStreakDisplay(habitCard, streak) {
        const streakElement = habitCard.querySelector('.streak-count');
        if (streakElement) {
            const oldStreak = parseInt(streakElement.textContent) || 0;
            streakElement.textContent = streak;
            
            // Animar si aumentó la racha
            if (streak > oldStreak) {
                streakElement.style.animation = 'bounceIn 0.5s ease-out';
            }
        }
    }

    updateUserStats(stats) {
        const elements = {
            'user-level': stats.level,
            'user-xp': stats.xp,
            'completed-today': stats.completed_today
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                const oldValue = parseInt(element.textContent) || 0;
                element.textContent = value;
                
                // Animar si el valor cambió
                if (value > oldValue) {
                    element.style.animation = 'numberChange 0.5s ease-out';
                }
            }
        });

        // Actualizar barra de progreso de nivel
        const levelProgress = document.getElementById('level-progress');
        if (levelProgress) {
            levelProgress.style.transition = 'width 1s ease-out';
            levelProgress.style.width = `${stats.progress}%`;
        }

        this.userStats = stats;
    }

    showXPGain(xpAmount) {
        const xpElement = document.createElement('div');
        xpElement.className = 'fixed top-20 right-20 bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-3 rounded-xl font-bold z-50 shadow-lg transform scale-0';
        xpElement.innerHTML = `<i class="fas fa-star mr-2"></i>+${xpAmount} XP`;
        
        document.body.appendChild(xpElement);

        // Animar entrada
        setTimeout(() => {
            xpElement.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
            xpElement.style.transform = 'scale(1) translateY(-10px)';
        }, 50);

        // Animar salida
        setTimeout(() => {
            xpElement.style.transform = 'scale(0.8) translateY(-50px)';
            xpElement.style.opacity = '0';
        }, 2000);

        setTimeout(() => {
            xpElement.remove();
        }, 2500);
    }

    showLevelUpModal(newLevel) {
        const modal = document.getElementById('levelup-modal');
        const levelElement = document.getElementById('new-level');
        
        if (levelElement) levelElement.textContent = newLevel;
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.animation = 'levelUpAnimation 1s ease-out';
        }

        // Reproducir sonido de level up (si está disponible)
        this.playLevelUpSound();
    }

    hideLevelUpModal() {
        const modal = document.getElementById('levelup-modal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    }

    hideAllModals() {
        this.hideConfirmModal();
        this.hideLevelUpModal();
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const colors = {
            success: 'from-green-500 to-green-600',
            error: 'from-red-500 to-red-600',
            warning: 'from-yellow-500 to-yellow-600',
            info: 'from-blue-500 to-blue-600'
        };

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        notification.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="${icons[type]} mr-3"></i>
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 50);

        // Auto-remover después de 5 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    showConfettiEffect() {
        // Crear efecto de confeti simple
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'fixed w-2 h-2 bg-yellow-400 z-50 pointer-events-none';
            confetti.style.left = Math.random() * window.innerWidth + 'px';
            confetti.style.top = '-10px';
            confetti.style.animation = `confettiFall ${Math.random() * 2 + 2}s linear`;
            
            document.body.appendChild(confetti);
            
            setTimeout(() => confetti.remove(), 4000);
        }
    }

    playLevelUpSound() {
        // Reproducir sonido usando Web Audio API o HTML5 Audio
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
            oscillator.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.1); // E5
            oscillator.frequency.setValueAtTime(783.99, audioContext.currentTime + 0.2); // G5
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch (error) {
            console.log('Audio not supported');
        }
    }

    async refreshData() {
        try {
            const response = await axios.get(`${this.apiBase}/data`);
            this.updateUIWithData(response.data);
        } catch (error) {
            console.error('Error refreshing data:', error);
        }
    }

    updateUIWithData(data) {
        // Actualizar estadísticas
        this.updateUserStats(data.userStats);
        
        // Actualizar datos internos
        this.userStats = data.userStats;
        
        // Aquí podrías actualizar las tarjetas si fuera necesario
        // Para una implementación completa, podrías regenerar las tarjetas
    }

    findHabitById(id, type) {
        // Esta función debería buscar en los datos actuales
        // Por simplicidad, buscamos en el DOM
        const card = document.querySelector(`[data-habit-id="${id}"][data-habit-type="${type}"]`);
        if (card) {
            return {
                id: id,
                name: card.querySelector('h3, h4')?.textContent || 'Hábito',
                type: type
            };
        }
        return null;
    }

    showCreateHabitForm() {
        // Redirigir al formulario de creación de hábitos
        window.location.href = '/formulario-habito';
    }

    scrollToSuggestions() {
        const suggestionsSection = document.querySelector('#suggested-habits-container');
        if (suggestionsSection) {
            suggestionsSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }

    showHabitStats(habitId, habitType) {
        // Implementar vista de estadísticas del hábito
        this.showNotification('Estadísticas próximamente disponibles', 'info');
    }
}

// Estilos CSS adicionales para las animaciones
const additionalStyles = `
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: scale(1); }
    to { opacity: 0; transform: scale(0.9); }
}

@keyframes completionPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    100% { transform: scale(1); }
}

@keyframes bounceIn {
    0% { transform: scale(0.3); }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); }
}

@keyframes numberChange {
    0% { transform: scale(1); color: inherit; }
    50% { transform: scale(1.2); color: #22c55e; }
    100% { transform: scale(1); color: inherit; }
}

@keyframes levelUpAnimation {
    0% { transform: scale(0.5) rotate(-5deg); opacity: 0; }
    50% { transform: scale(1.1) rotate(2deg); opacity: 0.8; }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

@keyframes slideOut {
    to { transform: translateX(100%); opacity: 0; }
}

@keyframes confettiFall {
    to { transform: translateY(100vh) rotate(720deg); opacity: 0; }
}
`;

// Inyectar estilos adicionales
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// Inicializar el componente cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.habitsManager = new HabitsManager();
});
