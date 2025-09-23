// Dashboard JavaScript - Separated from main dashboard file
// Contains all Alpine.js functionality and interactive behaviors

function habitApp() {
    return {
        userHabits: [],
        activeHabits: [],
        completedHabits: [],
        suggestions: { popular: [], by_category: {} },
        showSuggestions: false,
        isAdopting: false, // Estado para controlar la adopción
        showCreateModal: false,
        showEditModal: false,
        createForm: {
            step: 1,
            name: '',
            description: '',
            frequency: 'diario',
            category: 'bienestar',
            duration_days: '30',
            custom_duration: '30',
            motivation: '',
            generated_motivation: '',
            reward: '',
            start_date: new Date().toISOString().split('T')[0],
            selectedDays: []
        },
        toggleDay(index) {
            // Inicializar el array si no existe
            if (!this.createForm.selectedDays) {
                this.createForm.selectedDays = [];
            }
            
            // Convertir el índice a número
            index = Number(index);
            
            // Buscar el índice en el array actual
            const currentIndex = this.createForm.selectedDays.indexOf(index);
            
            // Si ya existe, lo quitamos
            if (currentIndex !== -1) {
                this.createForm.selectedDays.splice(currentIndex, 1);
            } else {
                // Si no existe, lo añadimos
                this.createForm.selectedDays.push(index);
            }
            
            // Forzar la reactividad creando un nuevo array
            this.createForm.selectedDays = Array.from(this.createForm.selectedDays).sort((a, b) => a - b);
        },
        editForm: {
            id: null,
            nombre: '',
            categoria: '',
            duration_days: 30,
            motivation: '',
            reward: ''
        },
        totalHabits: 0,
        userStats: {
            xp: 0, // Will be populated from server
            level: 1,
            progress: 0,
            next_level_xp: 100
        },
        notification: {
            show: false,
            message: ''
        },
        // Sistema de notificaciones de racha
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
        expandedHabit: null,
        fromExplorer: false, // Para recordar si se vino del explorador
        
        // Habit Explorer
        showHabitExplorer: false,
        explorerHabits: [],
        explorerLoading: false,
        explorerFilters: {
            search: '',
            category: 'all',
            sort: 'popularity'
        },
        explorerCategories: [
            { key: 'all', label: '<i class="fas fa-star mr-2"></i>Todos' },
            { key: 'salud', label: '<i class="fas fa-heartbeat mr-2"></i>Salud' },
            { key: 'productividad', label: '<i class="fas fa-briefcase mr-2"></i>Productividad' },
            { key: 'bienestar', label: '<i class="fas fa-smile mr-2"></i>Bienestar' },
            { key: 'aprendizaje', label: '<i class="fas fa-book mr-2"></i>Aprendizaje' },
            { key: 'finanzas', label: '<i class="fas fa-dollar-sign mr-2"></i>Finanzas' },
            { key: 'relaciones', label: '<i class="fas fa-heart mr-2"></i>Relaciones' }
        ],

        init() {
            this.loadUserHabits();
            this.loadSuggestions();
            this.loadStreakData();
            this.startStreakMonitoring();
            
            // 🧪 Hacer la instancia accesible para pruebas
            window.habitAppInstance = this;
        },

        // Cargar datos de racha del usuario
        async loadStreakData() {
            try {
                const response = await fetch('/api/user-streak');
                if (response.ok) {
                    const data = await response.json();
                    this.streakData = {
                        current: data.current_streak || 0,
                        best: data.best_streak || 0,
                        lastCompleted: data.last_completed,
                        hoursUntilReset: data.hours_until_reset || 0
                    };
                    // Solo verificar advertencias, no logs de debug constantes
                    this.checkStreakWarnings();
                } else {
                    console.error('❌ Error en respuesta del servidor:', response.status);
                }
            } catch (error) {
                console.error('❌ Error loading streak data:', error);
            }
        },

        // Monitoreo automático de rachas
        startStreakMonitoring() {
            // Verificar cada 30 minutos si hay riesgo de perder racha
            setInterval(() => {
                this.loadStreakData();
            }, 30 * 60 * 1000); // 30 minutos

            // Verificar advertencias más frecuentemente
            setInterval(() => {
                this.checkStreakWarnings();
            }, 5 * 60 * 1000); // 5 minutos
        },

        // Verificar si hay que mostrar advertencias de racha
        checkStreakWarnings() {
            // Solo verificar advertencias si hay una racha activa (> 0 días)
            if (this.streakData.current === 0 || !this.streakData.current) {
                return;
            }

            const hoursLeft = this.streakData.hoursUntilReset;
            
            // Solo mostrar advertencias si realmente hay riesgo
            if (hoursLeft <= 0) {
                return;
            }
            
            // Advertencia crítica - menos de 2 horas
            if (hoursLeft <= 2 && hoursLeft > 0) {
                this.showStreakNotification('warning', 
                    `⚠️ ¡Tu racha de ${this.streakData.current} días está en riesgo! Quedan menos de ${Math.ceil(hoursLeft)} horas`, 
                    this.streakData.current
                );
            }
            // Advertencia temprana - menos de 6 horas
            else if (hoursLeft <= 6 && hoursLeft > 2) {
                this.showStreakNotification('warning', 
                    `⏰ ¡Recuerda mantener tu racha de ${this.streakData.current} días! Quedan ${Math.ceil(hoursLeft)} horas`, 
                    this.streakData.current
                );
            }
        },

        // Mostrar notificación de racha específica
        showStreakNotification(type, message, days) {
            if (this.streakNotifications[type]) {
                this.streakNotifications[type] = {
                    show: true,
                    message: message,
                    days: days
                };
                
                console.log(`✅ Notificación ${type} configurada:`, this.streakNotifications[type]);

                // Auto-ocultar después de 8 segundos para notificaciones de racha
                setTimeout(() => {
                    if (this.streakNotifications[type]) {
                        this.streakNotifications[type].show = false;
                        console.log(`⏰ Auto-ocultando notificación ${type}`);
                    }
                }, 8000);
            } else {
                console.error(`❌ Tipo de notificación inválido: ${type}`);
            }
        },

        // Cerrar notificación de racha específica
        closeStreakNotification(type) {
            if (this.streakNotifications[type]) {
                this.streakNotifications[type].show = false;
            }
        },

        // Actualizar el display de racha en el header
        updateStreakDisplay() {
            const streakElement = document.querySelector('#streak-number');
            if (streakElement) {
                const currentDays = parseInt(streakElement.textContent) || 0;
                const newDays = this.streakData.current;
                
                if (newDays !== currentDays) {
                    // Animar el cambio de número
                    const streakCounter = document.querySelector('#streak-counter');
                    
                    // Añadir una animación especial al contador completo
                    if (streakCounter) {
                        streakCounter.style.transform = 'scale(1.1)';
                        streakCounter.style.transition = 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                    }
                    
                    streakElement.style.transform = 'scale(1.3)';
                    streakElement.style.color = '#fbbf24';
                    streakElement.style.transition = 'all 0.3s ease';
                    
                    setTimeout(() => {
                        streakElement.textContent = newDays;
                        if (streakCounter) {
                            streakCounter.style.transform = '';
                        }
                        streakElement.style.transform = '';
                        streakElement.style.color = '';
                    }, 200);
                }
            }
        },

        // Función para reproducir sonido de hábito completado
        playHabitCompleteSound() {
            console.log('🔊 Intentando reproducir sonido de hábito completado...');
            
            // Función helper para intentar reproducir
            const tryPlaySound = () => {
                if (window.SoundEffects) {
                    try {
                        window.SoundEffects.playHabitComplete();
                        return true;
                    } catch (error) {
                        console.error('❌ Error al reproducir sonido:', error);
                        return false;
                    }
                }
                return false;
            };
            
            // Intentar reproducir inmediatamente
            if (tryPlaySound()) {
                return;
            }
            
            // Intentar después de un pequeño delay
            setTimeout(() => {
                if (tryPlaySound()) {
                    return;
                }
                
                // Último intento después de más tiempo
                setTimeout(() => {
                    if (!tryPlaySound()) {
                        console.warn('⚠️ SoundEffects no pudo inicializarse. Verificar console para errores.');
                    }
                }, 1000);
            }, 200);
        },

        // Función para reproducir sonido de vida perdida (deshacer hábito)
        playLifeLostSound() {
            console.log('💔 Intentando reproducir sonido de vida perdida...');
            
            // Función helper para intentar reproducir
            const tryPlaySound = () => {
                if (window.SoundEffects) {
                    try {
                        window.SoundEffects.playLifeLost();
                        return true;
                    } catch (error) {
                        console.error('❌ Error al reproducir sonido:', error);
                        return false;
                    }
                }
                return false;
            };
            
            // Intentar reproducir inmediatamente
            if (tryPlaySound()) {
                return;
            }
            
            // Intentar después de un pequeño delay
            setTimeout(() => {
                if (tryPlaySound()) {
                    return;
                }
                
                // Último intento después de más tiempo
                setTimeout(() => {
                    if (!tryPlaySound()) {
                        console.warn('⚠️ SoundEffects no pudo inicializarse. Verificar console para errores.');
                    }
                }, 1000);
            }, 200);
        },

        async loadUserHabits() {
            try {
                const response = await fetch('/api/user-habits');
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                this.activeHabits = data.active_habits || [];
                this.completedHabits = data.completed_today || [];
                this.userHabits = [...this.activeHabits, ...this.completedHabits];
                this.totalHabits = this.userHabits.length;
                this.userStats = data.user_stats;
                
                // Auto-mostrar sugerencias si no hay hábitos
                if (this.userHabits.length === 0) {
                    this.showSuggestions = true;
                }
                
                // Actualizar el estado de las sugerencias (marcar ya agregadas)
                if (this.suggestions) {
                    this.markAlreadyAddedSuggestions(this.suggestions);
                }
            } catch (error) {
                console.error('[ERROR] Error loading habits:', error);
                this.showNotification('Error al cargar los hábitos');
            }
        },

        reorganizeHabits() {
            // Separar hábitos completados y pendientes
            const completedToday = this.userHabits.filter(h => h.today_completed || h.status === 'completed');
            const pending = this.userHabits.filter(h => !h.today_completed && h.status !== 'completed');
            
            // Reorganizar: pendientes primero, completados al final
            this.userHabits = [...pending, ...completedToday];
            this.activeHabits = pending;
            this.completedHabits = completedToday;
        },

        async loadSuggestions() {
            try {
                // Volver al endpoint original que ya funcionaba
                const response = await fetch('/api/suggestions');
                const suggestions = await response.json();
                
                // Marcar sugerencias que ya están agregadas
                this.markAlreadyAddedSuggestions(suggestions);
                this.suggestions = suggestions;
            } catch (error) {
                console.error('Error loading suggestions:', error);
            }
        },

        // Función para marcar sugerencias ya agregadas
        markAlreadyAddedSuggestions(suggestions) {
            if (!this.userHabits || !suggestions) return;
            
            // Crear un Set con los nombres de hábitos activos del usuario
            const activeHabitNames = new Set(
                this.userHabits
                    .filter(habit => habit.is_active)
                    .map(habit => (habit.nombre || habit.name || '').toLowerCase().trim())
            );
            
            // Marcar sugerencias populares
            if (suggestions.popular) {
                suggestions.popular.forEach(suggestion => {
                    suggestion.already_added = activeHabitNames.has(
                        (suggestion.name || '').toLowerCase().trim()
                    );
                });
            }
            
            // Marcar sugerencias por categoría
            if (suggestions.by_category) {
                Object.keys(suggestions.by_category).forEach(category => {
                    if (suggestions.by_category[category]) {
                        suggestions.by_category[category].forEach(suggestion => {
                            suggestion.already_added = activeHabitNames.has(
                                (suggestion.name || '').toLowerCase().trim()
                            );
                        });
                    }
                });
            }
        },

        async refreshData() {
            if (this.isRefreshing) return; // Prevenir múltiples clics
            
            this.isRefreshing = true;
            try {
                // Mostrar notificación de inicio
                this.showNotification('Actualizando sugerencias...');
                
                // Cargar nuevas sugerencias aleatorias
                const response = await fetch('/api/suggestions?refresh=true&random=' + Math.random());
                const newSuggestions = await response.json();
                
                // Actualizar las sugerencias con las nuevas
                this.suggestions = newSuggestions;
                
                // También recargar hábitos del usuario para actualizar el estado
                await this.loadUserHabits();
                
                // Mostrar notificación de éxito
                this.showNotification('¡Nuevas sugerencias cargadas!');
                
            } catch (error) {
                console.error('Error refreshing data:', error);
                this.showNotification('Error al actualizar las sugerencias. Inténtalo de nuevo.');
            } finally {
                this.isRefreshing = false;
            }
        },

        async completeHabit(habit) {
            try {
                // Actualizar el estado local inmediatamente para feedback visual
                const habitIndex = this.userHabits.findIndex(h => h.id === habit.id);
                if (habitIndex !== -1) {
                    this.userHabits[habitIndex].today_completed = true;
                    this.userHabits[habitIndex].status = 'completed';
                }
                
                // Actualizar también en activeHabits si existe
                const activeIndex = this.activeHabits.findIndex(h => h.id === habit.id);
                if (activeIndex !== -1) {
                    this.activeHabits[activeIndex].today_completed = true;
                    this.activeHabits[activeIndex].status = 'completed';
                }

                const response = await fetch(`/habits/${habit.id}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message);
                    
                    // Reproducir sonido de hábito completado
                    this.playHabitCompleteSound();
                    
                    // Actualizar el hábito específico con datos del servidor si están disponibles
                    if (data.habit) {
                        if (habitIndex !== -1) {
                            this.userHabits[habitIndex] = { ...this.userHabits[habitIndex], ...data.habit };
                        }
                        if (activeIndex !== -1) {
                            this.activeHabits[activeIndex] = { ...this.activeHabits[activeIndex], ...data.habit };
                        }
                    }
                    
                    // Actualizar stats del usuario si están en la respuesta
                    if (data.user_stats) {
                        this.userStats = data.user_stats;
                    }

                    // Manejar notificaciones de racha
                    if (data.streak_data) {
                        const oldCurrent = this.streakData.current;
                        const oldBest = this.streakData.best;
                        
                        this.streakData = {
                            current: data.streak_data.current || 0,
                            best: data.streak_data.best || 0,
                            lastCompleted: data.streak_data.last_completed,
                            hoursUntilReset: data.streak_data.hours_until_reset || 0
                        };

                        // Mostrar notificación basada en la respuesta del servidor
                        if (data.streak_data.notification_type && data.streak_data.notification_message) {
                            console.log('🔔 Mostrando notificación de racha:', data.streak_data.notification_type, data.streak_data.notification_message);
                            this.showStreakNotification(
                                data.streak_data.notification_type, 
                                data.streak_data.notification_message, 
                                this.streakData.current
                            );
                        }

                        // Actualizar el contador de racha en el header si existe
                        this.updateStreakDisplay();
                    }
                    
                    // Verificar level-up y mostrar confetti
                    if (data.leveled_up) {
                        setTimeout(() => {
                            this.launchConfetti();
                            this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                            
                            // Reproducir sonido épico de subida de nivel
                            if (window.SoundEffects) {
                                window.SoundEffects.playLevelUp(data.new_level);
                            }
                        }, 500);
                    }
                    
                    // Reordenar hábitos para mover completados al final
                    this.reorganizeHabits();
                    
                } else {
                    // Si falló, revertir el cambio local
                    if (habitIndex !== -1) {
                        this.userHabits[habitIndex].today_completed = false;
                        this.userHabits[habitIndex].status = 'active';
                    }
                    if (activeIndex !== -1) {
                        this.activeHabits[activeIndex].today_completed = false;
                        this.activeHabits[activeIndex].status = 'active';
                    }
                    this.showNotification(data.message);
                }
            } catch (error) {
                console.error('Error completing habit:', error);
                // Revertir cambios en caso de error
                const habitIndex = this.userHabits.findIndex(h => h.id === habit.id);
                if (habitIndex !== -1) {
                    this.userHabits[habitIndex].today_completed = false;
                    this.userHabits[habitIndex].status = 'active';
                }
                const activeIndex = this.activeHabits.findIndex(h => h.id === habit.id);
                if (activeIndex !== -1) {
                    this.activeHabits[activeIndex].today_completed = false;
                    this.activeHabits[activeIndex].status = 'active';
                }
                this.showNotification('Error al completar el hábito. Inténtalo de nuevo.');
            }
        },

        async adoptSuggestion(suggestion) {
            this.isAdopting = true;
            try {
                const response = await fetch('/habits/create-from-suggestion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        suggestion_id: suggestion.id,
                        frequency: 'diario',
                        duration_days: 30,
                        motivation: suggestion.benefits,
                        reward: 'Sentirme mejor conmigo mismo'
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message);
                    
                    // Actualizar stats del usuario si están en la respuesta
                    if (data.user_stats) {
                        this.userStats = data.user_stats;
                    }
                    
                    // Verificar level-up y mostrar confetti
                    if (data.leveled_up) {
                        setTimeout(() => {
                            this.launchConfetti();
                            this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                            
                            // Reproducir sonido épico de subida de nivel
                            if (window.SoundEffects) {
                                window.SoundEffects.playLevelUp(data.new_level);
                            }
                        }, 500);
                    }
                    
                    // Recargar hábitos y sugerencias para actualizar la vista
                    await this.loadUserHabits();
                    await this.loadSuggestions(); // Recargar sugerencias sin duplicados
                } else {
                    // Manejar diferentes tipos de errores
                    if (data.error_type === 'duplicate_habit') {
                        this.showNotification('⚠️ ' + data.message);
                        // Marcar esta sugerencia como ya agregada
                        suggestion.already_added = true;
                    } else {
                        this.showNotification(data.message || 'Error al adoptar el hábito');
                    }
                }
            } catch (error) {
                console.error('Error adopting suggestion:', error);
                this.showNotification('Error al adoptar el hábito. Inténtalo de nuevo.');
            } finally {
                this.isAdopting = false;
            }
        },

        // Funciones para el formulario de creación
        nextStep() {
            // Validaciones por paso
            if (this.createForm.step === 1) {
                if (!this.createForm.name.trim()) {
                    this.showNotification('Por favor, ingresa el nombre del hábito');
                    return;
                }
            } else if (this.createForm.step === 2) {
                if (this.createForm.frequency === 'semanal' && this.createForm.selectedDays.length === 0) {
                    this.showNotification('Por favor, selecciona al menos un día de la semana');
                    return;
                }
            } else if (this.createForm.step === 3) {
                if (!this.createForm.motivation.trim()) {
                    this.showNotification('Por favor, describe tu motivación');
                    return;
                }
            }
            
            if (this.createForm.step < 5) {
                this.createForm.step++;
            }
        },

        async submitCreateForm() {
            try {
                if (this.createForm.frequency === 'semanal' && (!this.createForm.selectedDays || this.createForm.selectedDays.length === 0)) {
                    this.showNotification('Por favor, selecciona al menos un día de la semana');
                    return;
                }

                console.log('Tipo de selectedDays:', typeof this.createForm.selectedDays);
                console.log('Es array?', Array.isArray(this.createForm.selectedDays));
                console.log('Valor de selectedDays:', this.createForm.selectedDays);
                console.log('Contenido completo del formulario:', this.createForm);

                // Validar los días seleccionados para frecuencia semanal
                if (this.createForm.frequency === 'semanal') {
                    if (!Array.isArray(this.createForm.selectedDays) || this.createForm.selectedDays.length === 0) {
                        this.showNotification('Por favor, selecciona al menos un día de la semana');
                        return;
                    }
                }
                
                // Preparar los días seleccionados como un array de números
                const selected_days = this.createForm.frequency === 'semanal'
                    ? Array.from(this.createForm.selectedDays).map(Number)
                    : [];
                
                console.log('Formulario a enviar:', {
                    frequency: this.createForm.frequency,
                    selected_days: selected_days
                });

                const formData = {
                    name: this.createForm.name,
                    description: this.createForm.description || '',
                    frequency: this.createForm.frequency,
                    categoria: this.createForm.category,
                    duration_days: this.getFinalDuration(),
                    motivation: this.createForm.motivation,
                    reward: this.createForm.reward || '',
                    start_date: this.createForm.start_date,
                    selected_days: selected_days
                };

                console.log('Datos del formulario:', formData);

                const response = await fetch('/habits', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    console.error('Error en la respuesta:', data);
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\\n');
                        this.showNotification(errorMessages);
                    } else {
                        this.showNotification(data.message || 'Error al crear el hábito');
                    }
                    return;
                }
                
                if (data.success) {
                    this.showNotification(data.message);
                    this.showCreateModal = false;
                    this.resetCreateForm();
                    
                    // Actualizar stats del usuario si están en la respuesta
                    if (data.user_stats) {
                        this.userStats = data.user_stats;
                    }
                    
                    // Verificar level-up y mostrar confetti
                    if (data.leveled_up) {
                        setTimeout(() => {
                            this.launchConfetti();
                            this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                            
                            // Reproducir sonido épico de subida de nivel
                            if (window.SoundEffects) {
                                window.SoundEffects.playLevelUp(data.new_level);
                            }
                        }, 500);
                    }
                    
                    await this.loadUserHabits(); // Recargar la lista de hábitos
                } else {
                    this.showNotification(data.message || 'Error al crear el hábito');
                }
            } catch (error) {
                console.error('Error creating habit:', error);
                this.showNotification('Error al crear el hábito. Inténtalo de nuevo.');
            }
        },

        resetCreateForm() {
            this.createForm = {
                step: 1,
                name: '',
                description: '',
                frequency: 'diario',
                category: 'bienestar',
                duration_days: '30',
                custom_duration: '30',
                motivation: '',
                generated_motivation: '',
                reward: '',
                start_date: new Date().toISOString().split('T')[0],
                selectedDays: new Array()
            };
        },

        // Generador de frases motivadoras basado en la categoría
        generateMotivationalPhrase() {
            const phrases = {
                bienestar: [
                    "Cada día que cuido mi bienestar, invierto en mi mejor versión.",
                    "Mi salud y felicidad son mi mayor prioridad.",
                    "Merezco dedicar tiempo a cuidarme y sentirme bien.",
                    "Cada pequeño paso hacia mi bienestar cuenta.",
                    "Soy responsable de crear la vida que deseo vivir."
                ],
                productividad: [
                    "Cada día soy más eficiente y logro mis objetivos.",
                    "Mi disciplina de hoy construye el éxito de mañana.",
                    "Cada tarea completada me acerca a mis metas.",
                    "Organizar mi tiempo es organizar mi vida.",
                    "La productividad no es trabajar más, sino trabajar mejor."
                ],
                finanzas: [
                    "Cada peso ahorrado es un paso hacia mi libertad financiera.",
                    "Mis decisiones financieras de hoy aseguran mi futuro.",
                    "Merezco tener estabilidad y prosperidad económica.",
                    "Administrar bien mi dinero es cuidar mi futuro.",
                    "Cada día mejoro mi relación con el dinero."
                ],
                relaciones: [
                    "Las relaciones fuertes se construyen día a día.",
                    "Invertir tiempo en mis seres queridos es la mejor inversión.",
                    "Cada conversación sincera fortalece nuestros vínculos.",
                    "Merezco relaciones llenas de amor y respeto mutuo.",
                    "Ser presente para otros me hace crecer como persona."
                ],
                salud: [
                    "Mi cuerpo es mi templo y merece el mejor cuidado.",
                    "Cada día elijo opciones que nutren mi salud.",
                    "Cuidar mi salud hoy me da energía para el mañana.",
                    "Soy capaz de crear hábitos que transforman mi bienestar.",
                    "Mi salud es la base de todo lo que quiero lograr."
                ],
                aprendizaje: [
                    "Cada día que aprendo algo nuevo, crezco como persona.",
                    "Mi mente es como un músculo que se fortalece con el uso.",
                    "El conocimiento que adquiero hoy abre puertas mañana.",
                    "Nunca es tarde para aprender algo que me apasione.",
                    "Cada libro, curso o experiencia me hace más completo."
                ]
            };

            const categoryPhrases = phrases[this.createForm.category] || phrases.bienestar;
            const randomPhrase = categoryPhrases[Math.floor(Math.random() * categoryPhrases.length)];
            this.createForm.generated_motivation = randomPhrase;
        },

        // Usar la frase generada como motivación
        useGeneratedMotivation() {
            this.createForm.motivation = this.createForm.generated_motivation;
        },

        // Obtener duración final considerando personalizada
        getFinalDuration() {
            return this.createForm.duration_days === 'custom' 
                ? parseInt(this.createForm.custom_duration) 
                : parseInt(this.createForm.duration_days);
        },

        showNotification(message) {
            this.notification.message = message;
            this.notification.show = true;
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        },

        // Función para lanzar confeti cuando sube de nivel
        launchConfetti() {
            // Confeti desde arriba
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
            });

            // Confeti lateral izquierdo
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0, y: 0.8 },
                    colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                });
            }, 200);

            // Confeti lateral derecho
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1, y: 0.8 },
                    colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                });
            }, 400);

            // Confeti final desde el centro
            setTimeout(() => {
                confetti({
                    particleCount: 150,
                    spread: 360,
                    startVelocity: 30,
                    decay: 0.9,
                    scalar: 1.2,
                    origin: { x: 0.5, y: 0.5 },
                    colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
                });
            }, 600);
        },

        showCategoryDetails(category, habits) {
            // Crear un modal dinámico para mostrar hábitos de la categoría
            if (habits && habits.length > 0) {
                const categoryName = category.charAt(0).toUpperCase() + category.slice(1);
                const icon = this.getCategoryIcon(category);
                
                let habitList = habits.map(habit => 
                    `<div class="flex items-center justify-between p-3 bg-white/5 rounded-lg mb-2">
                        <div class="flex items-center space-x-3">
                            <span class="text-xl">${habit.icon}</span>
                            <div>
                                <div class="text-white font-medium">${habit.name}</div>
                                <div class="text-white/60 text-sm">${habit.description}</div>
                            </div>
                        </div>
                        <button onclick="habitApp().adoptSuggestionById(${habit.id})" 
                                class="bg-motiveo-primary hover:bg-motiveo-primary/80 px-3 py-1 rounded text-sm text-white">
                            Adoptar
                        </button>
                    </div>`
                ).join('');

                this.showNotification(`${icon} ${categoryName}: ${habits.length} hábitos disponibles`);
            }
        },

        async adoptSuggestionById(suggestionId) {
            const suggestion = this.findSuggestionById(suggestionId);
            if (suggestion) {
                await this.adoptSuggestion(suggestion);
            }
        },

        findSuggestionById(id) {
            // Buscar en sugerencias populares
            let suggestion = this.suggestions.popular.find(s => s.id === id);
            if (suggestion) return suggestion;
            
            // Buscar en categorías
            for (let category in this.suggestions.by_category) {
                suggestion = this.suggestions.by_category[category].find(s => s.id === id);
                if (suggestion) return suggestion;
            }
            return null;
        },

        getCategoryIcon(categoria) {
            // Iconos animados Lottie directos
            const icons = {
                'salud': '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'productividad': '<lottie-player src="/animations/productivity.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'bienestar': '<lottie-player src="/animations/wellbeing.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'aprendizaje': '<lottie-player src="/animations/learning.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'finanzas': '<lottie-player src="/animations/finances.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'relaciones': '<lottie-player src="/animations/relationships.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'ejercicio': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'fitness': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'deporte': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>',
                'correr': '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>'
            };
            return icons[categoria] || '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
        },

        // Nueva función para detectar hábitos de ejercicio por nombre/descripción
        getHabitIcon(habit) {
            const nombre = habit.nombre?.toLowerCase() || '';
            const descripcion = habit.descripcion?.toLowerCase() || '';
            const categoria = habit.categoria?.toLowerCase() || '';
            
            // Palabras clave relacionadas con ejercicio/correr
            const ejercicioKeywords = ['correr', 'running', 'trotar', 'caminar', 'walk', 'ejercicio', 'gym', 'gimnasio', 'fitness', 'entrenamiento', 'cardio', 'deportes', 'deporte'];
            const saludKeywords = ['salud', 'dormir', 'vitaminas', 'medicina', 'doctor', 'hospital', 'nutrición'];
            const aguaKeywords = ['agua', 'hidrat', 'beber', 'líquido', 'water'];
            const meditacionKeywords = ['meditar', 'meditation', 'mindfulness', 'relajar', 'yoga', 'respirar', 'calma', 'zen'];
            const lecturaKeywords = ['leer', 'lectura', 'libro', 'estudiar', 'aprender', 'curso', 'educación'];
            const finanzasKeywords = ['dinero', 'ahorro', 'presupuesto', 'invertir', 'finanzas', 'económico', 'gastos'];
            const relacionesKeywords = ['familia', 'amigos', 'pareja', 'social', 'comunicar', 'amor', 'relación'];
            const productividadKeywords = ['trabajo', 'productividad', 'planificar', 'organizar', 'metas', 'objetivos', 'tareas'];
            
            const esEjercicio = ejercicioKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esSalud = saludKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esAgua = aguaKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword)
            );
            
            const esMeditacion = meditacionKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esLectura = lecturaKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esFinanzas = finanzasKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esRelaciones = relacionesKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            const esProductividad = productividadKeywords.some(keyword => 
                nombre.includes(keyword) || descripcion.includes(keyword) || categoria.includes(keyword)
            );
            
            if (esEjercicio) {
                return '<lottie-player src="/animations/run.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esAgua) {
                return '<lottie-player src="/animations/water.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esSalud) {
                return '<lottie-player src="/animations/health.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esMeditacion) {
                return '<lottie-player src="/animations/meditation.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esLectura) {
                return '<lottie-player src="/animations/learning.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esFinanzas) {
                return '<lottie-player src="/animations/finances.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esRelaciones) {
                return '<lottie-player src="/animations/relationships.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            } else if (esProductividad) {
                return '<lottie-player src="/animations/productivity.json" background="transparent" speed="1" style="width: 64px; height: 64px;" loop autoplay></lottie-player>';
            }
            
            // Si no encuentra coincidencias específicas, usa el icono de categoría
            return this.getCategoryIcon(categoria);
        },

        // Nuevas funciones para el modal expandido
        expandHabit(habit) {
            this.expandedHabit = habit;
        },

        async handleHabitAction(habitId, action) {
            try {
                let endpoint = '';
                let method = 'POST';
                
                if (action === 'complete') {
                    endpoint = `/habits/${habitId}/complete`;
                } else if (action === 'undo') {
                    endpoint = `/habits/${habitId}/undo`;
                }

                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message);
                    
                    // Reproducir sonido según la acción y prioridad
                    if (action === 'undo') {
                        // Sonido de vida perdida al deshacer un hábito
                        this.playLifeLostSound();
                    } else if (action === 'complete') {
                        // Solo reproducir sonido de hábito completado si NO hay level-up
                        // Si hay level-up, el sonido épico de nivel tendrá prioridad
                        if (!data.leveled_up) {
                            this.playHabitCompleteSound();
                        }
                    }
                    
                    // Actualizar el hábito específico con los nuevos datos del backend
                    if (data.habit) {
                        // Encontrar y actualizar el hábito en la lista principal
                        const habitIndex = this.userHabits.findIndex(h => h.id === habitId);
                        if (habitIndex !== -1) {
                            this.userHabits[habitIndex] = { ...this.userHabits[habitIndex], ...data.habit };
                        }
                        
                        // Actualizar también en activeHabits si existe
                        const activeIndex = this.activeHabits.findIndex(h => h.id === habitId);
                        if (activeIndex !== -1) {
                            this.activeHabits[activeIndex] = { ...this.activeHabits[activeIndex], ...data.habit };
                        }
                        
                        // Actualizar también en completedHabits si existe
                        const completedIndex = this.completedHabits.findIndex(h => h.id === habitId);
                        if (completedIndex !== -1) {
                            this.completedHabits[completedIndex] = { ...this.completedHabits[completedIndex], ...data.habit };
                        }
                        
                        // Actualizar el hábito expandido inmediatamente
                        if (this.expandedHabit && this.expandedHabit.id === habitId) {
                            this.expandedHabit = { ...this.expandedHabit, ...data.habit };
                        }
                    }
                    
                    // Actualizar stats del usuario si están en la respuesta
                    if (data.user_stats) {
                        this.userStats = data.user_stats;
                    }
                    
                    // Verificar level-up y mostrar confetti
                    if (data.leveled_up) {
                        setTimeout(() => {
                            this.launchConfetti();
                            this.showNotification(`¡Felicidades! ¡Subiste al nivel ${data.new_level}!`);
                            
                            // Reproducir sonido épico de subida de nivel
                            if (window.SoundEffects) {
                                window.SoundEffects.playLevelUp(data.new_level);
                            }
                        }, 500);
                    }
                    
                    // Solo recargar hábitos si no tenemos datos específicos del hábito
                    if (!data.habit) {
                        await this.loadUserHabits();
                        
                        // Actualizar el hábito expandido con los nuevos datos
                        if (this.expandedHabit && this.expandedHabit.id === habitId) {
                            const updatedHabit = this.userHabits.find(h => h.id === habitId);
                            if (updatedHabit) {
                                this.expandedHabit = updatedHabit;
                            }
                        }
                    }
                } else {
                    this.showNotification(data.message);
                }
            } catch (error) {
                console.error('Error handling habit action:', error);
                this.showNotification('Error al procesar la acción. Inténtalo de nuevo.');
            }
        },

        getHabitSteps(habit) {
            if (!habit) return [];
            
            // Generar pasos basados en la categoría del hábito
            const stepsByCategory = {
                'salud': [
                    'Prepara el espacio adecuado para la actividad',
                    'Comienza con una intensidad moderada',
                    'Mantén un ritmo constante durante la actividad',
                    'Escucha a tu cuerpo y ajusta según sea necesario',
                    'Registra tu progreso y cómo te sientes'
                ],
                'productividad': [
                    'Elimina las distracciones de tu entorno',
                    'Define objetivos claros para esta sesión',
                    'Organiza las tareas por prioridad',
                    'Trabaja con bloques de tiempo concentrado',
                    'Evalúa lo logrado y planifica el siguiente paso'
                ],
                'bienestar': [
                    'Encuentra un momento de tranquilidad',
                    'Respira profundamente y relájate',
                    'Concéntrate en el presente y tus sensaciones',
                    'Dedica tiempo completo a esta actividad',
                    'Reflexiona sobre los beneficios obtenidos'
                ],
                'aprendizaje': [
                    'Prepara los materiales necesarios',
                    'Revisa brevemente el contenido anterior',
                    'Enfócate en comprender conceptos clave',
                    'Practica lo aprendido con ejemplos',
                    'Toma notas y resume los puntos importantes'
                ]
            };

            // Pasos genéricos si la categoría no está definida
            const genericSteps = [
                'Prepárate mental y físicamente para la actividad',
                'Comienza con enfoque y determinación',
                'Mantén la constancia durante todo el proceso',
                'Supera cualquier resistencia que puedas sentir',
                'Celebra haber completado este hábito positivo'
            ];

            return stepsByCategory[habit.category] || stepsByCategory[habit.categoria] || genericSteps;
        },

        showEditHabit(habit) {
            // Cerrar el modal expandido usando la función apropiada
            this.closeHabitDetails();
            
            // Llenar el formulario de edición con los datos del hábito
            this.editForm = {
                id: habit.id,
                nombre: habit.nombre,
                categoria: habit.categoria,
                duration_days: habit.duration_days,
                motivation: habit.motivation || '',
                reward: habit.reward || ''
            };
            
            // Mostrar el modal de edición
            this.showEditModal = true;
        },

        async updateHabit() {
            try {
                const response = await fetch(`/habits/${this.editForm.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nombre: this.editForm.nombre,
                        categoria: this.editForm.categoria,
                        duration_days: this.editForm.duration_days,
                        motivation: this.editForm.motivation,
                        reward: this.editForm.reward
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification('Hábito actualizado exitosamente');
                    this.showEditModal = false;
                    await this.loadUserHabits(); // Recargar hábitos
                } else {
                    this.showNotification('Error al actualizar el hábito');
                }
            } catch (error) {
                console.error('Error updating habit:', error);
                this.showNotification('Error al actualizar el hábito');
            }
        },

        confirmDeleteHabit(habit) {
            if (confirm(`¿Estás seguro de que quieres eliminar el hábito "${habit.nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                this.deleteHabit(habit.id);
            }
        },

        async deleteHabit(habitId) {
            try {
                const response = await fetch(`/habits/${habitId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification('Hábito eliminado exitosamente');
                    this.closeHabitDetails(); // Cerrar modal expandido si está abierto
                    await this.loadUserHabits(); // Recargar hábitos
                } else {
                    this.showNotification('Error al eliminar el hábito');
                }
            } catch (error) {
                console.error('Error deleting habit:', error);
                this.showNotification('Error al eliminar el hábito');
            }
        },

        // Habit Explorer Methods
        async openHabitExplorer() {
            this.showHabitExplorer = true;
            await this.loadAllHabits();
        },

        async loadAllHabits() {
            this.explorerLoading = true;
            try {
                const params = new URLSearchParams({
                    search: this.explorerFilters.search,
                    category: this.explorerFilters.category,
                    sort: this.explorerFilters.sort
                });

                console.log('Fetching:', `/habits/suggestions?${params}`);
                const response = await fetch(`/habits/suggestions?${params}`);
                const data = await response.json();
                
                console.log('Response data:', data);
                this.explorerHabits = data.suggestions || [];
                console.log('Explorer habits loaded:', this.explorerHabits.length);
            } catch (error) {
                console.error('Error loading all habits:', error);
                this.showNotification('Error al cargar los hábitos');
            } finally {
                this.explorerLoading = false;
            }
        },

        async searchHabits() {
            await this.loadAllHabits();
        },

        async adoptSuggestionFromExplorer(habit) {
            try {
                console.log('[ADOPT] Adoptando hábito:', habit);
                this.isAdopting = true;
                
                const response = await fetch(`/habits/suggestions/${habit.id}/add`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                console.log('[RESPONSE] Respuesta del servidor:', data);
                
                if (data.success) {
                    this.showNotification(`${habit.name} agregado a Mis Hábitos!`);
                    
                    // Celebration
                    this.launchConfetti();
                    
                    // Pequeño delay para asegurar que la DB se actualice
                    await new Promise(resolve => setTimeout(resolve, 500));
                    
                    // Update UI - con logs de depuración
                    console.log('[RELOAD] Recargando hábitos del usuario...');
                    await this.loadUserHabits();
                    console.log('[SUCCESS] Hábitos del usuario recargados. Total:', this.userHabits.length);
                    
                    await this.loadSuggestions();
                    
                    // Optionally close explorer after adoption
                    // this.showHabitExplorer = false;
                } else {
                    console.error('[ERROR] Error en la respuesta:', data);
                    this.showNotification(data.message || 'Error al agregar el hábito');
                }
            } catch (error) {
                console.error('Error adopting habit from explorer:', error);
                this.showNotification('Error al agregar el hábito');
            } finally {
                this.isAdopting = false;
            }
        },

        showHabitDetails(habit) {
            // Recordar que venimos del explorador
            this.fromExplorer = this.showHabitExplorer;
            
            // Cerrar el explorador de hábitos
            this.showHabitExplorer = false;
            
            // Show detailed view of habit in a modal or expanded view
            this.expandedHabit = {
                ...habit,
                is_completed: false,
                current_streak: 0,
                best_streak: 0,
                type: 'suggested'
            };
        },

        closeHabitDetails() {
            this.expandedHabit = null;
            
            // Si veníamos del explorador, reabrirlo
            if (this.fromExplorer) {
                this.showHabitExplorer = true;
                this.fromExplorer = false; // Reset para futuras aperturas
            }
        },

        async debugHabits() {
            try {
                console.log('[DEBUG] Depurando hábitos...');
                const response = await fetch('/debug/habits');
                const data = await response.json();
                console.log('[DEBUG] Datos de depuración:', data);
                this.showNotification(`Debug: ${data.total_habits} hábitos encontrados`);
            } catch (error) {
                console.error('[ERROR] Error en debug:', error);
                this.showNotification('Error en debug');
            }
        },

        getCategoryStyle(categoria) {
            const styles = {
                'salud': 'bg-red-500/20',
                'productividad': 'bg-blue-500/20',
                'bienestar': 'bg-purple-500/20',
                'aprendizaje': 'bg-yellow-500/20',
                'finanzas': 'bg-green-500/20',
                'relaciones': 'bg-pink-500/20'
            };
            return styles[categoria] || 'bg-gray-500/20';
        }
    };
}

// Dynamic animations and effects
document.addEventListener('DOMContentLoaded', function() {
    // Animación de carga inicial
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);

    // Efectos de partículas en hover para botones importantes
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('mouseenter', function(e) {
            if (this.classList.contains('animate-pulse-button')) {
                this.style.boxShadow = '0 0 30px rgba(139, 92, 246, 0.6)';
                this.style.transform = 'scale(1.05) translateY(-2px)';
            }
        });

        button.addEventListener('mouseleave', function(e) {
            this.style.boxShadow = '';
            this.style.transform = '';
        });

        // Efecto de onda al hacer clic
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                z-index: 1;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        });
    });

    // Animación de números contadores
    function animateCounter(element, target, duration = 1000) {
        const start = parseInt(element.textContent) || 0;
        const increment = (target - start) / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= target) || (increment < 0 && current <= target)) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.round(current);
        }, 16);
    }

    // Observador para animaciones en scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'cardAppear 0.6s ease-out forwards';
                
                // Animar contadores si los encuentra
                const counters = entry.target.querySelectorAll('[data-counter]');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-counter'));
                    animateCounter(counter, target);
                });
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Efectos de paralaje suave en scroll
    let ticking = false;
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-parallax') || 0.5;
            const transform = `translateY(${scrolled * speed}px)`;
            element.style.transform = transform;
        });
        
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    });

    // Efecto de cursor personalizado para áreas interactivas
    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    cursor.style.cssText = `
        position: fixed;
        width: 20px;
        height: 20px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.8) 0%, rgba(139, 92, 246, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        mix-blend-mode: difference;
        transition: transform 0.1s ease;
        display: none;
    `;
    document.body.appendChild(cursor);

    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX - 10 + 'px';
        cursor.style.top = e.clientY - 10 + 'px';
        cursor.style.display = 'block';
    });

    document.addEventListener('mouseleave', () => {
        cursor.style.display = 'none';
    });

    // Mejorar hover effects para tarjetas
    document.querySelectorAll('.habit-card, .suggestion-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});

// Agregar animación de ripple en CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }
`;
document.head.appendChild(style);
