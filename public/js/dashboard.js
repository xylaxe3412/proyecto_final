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
            duration_days: 30,
            motivation: '',
            reward: '',
            start_date: new Date().toISOString().split('T')[0]
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
        },

        // Función para reproducir sonido de hábito completado
        playHabitCompleteSound() {
            console.log('🔊 Intentando reproducir sonido de hábito completado...');
            
            // Función helper para intentar reproducir
            const tryPlaySound = () => {
                if (window.SoundEffects) {
                    try {
                        window.SoundEffects.playHabitComplete();
                        console.log('✅ Sonido de hábito completado reproducido exitosamente');
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
                        console.log('✅ Sonido de vida perdida reproducido exitosamente');
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
                console.log('[LOADING] Cargando hábitos del usuario...');
                const response = await fetch('/api/user-habits');
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('[DATA] Datos recibidos:', data);
                
                this.activeHabits = data.active_habits || [];
                this.completedHabits = data.completed_today || [];
                this.userHabits = [...this.activeHabits, ...this.completedHabits];
                this.totalHabits = this.userHabits.length;
                this.userStats = data.user_stats;
                
                console.log('[SUCCESS] Hábitos cargados:', {
                    activos: this.activeHabits.length,
                    completados: this.completedHabits.length,
                    total: this.userHabits.length
                });
                
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
                const response = await fetch('/habits', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: this.createForm.name,
                        description: this.createForm.description,
                        frequency: this.createForm.frequency,
                        categoria: this.createForm.category,
                        duration_days: this.createForm.duration_days,
                        motivation: this.createForm.motivation,
                        reward: this.createForm.reward,
                        start_date: this.createForm.start_date
                    })
                });

                const data = await response.json();
                
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
                duration_days: 30,
                motivation: '',
                reward: '',
                start_date: new Date().toISOString().split('T')[0]
            };
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
                    
                    // Debug: mostrar datos recibidos
                    console.log('[HABIT ACTION] Datos recibidos del servidor:', data);
                    console.log('[HABIT ACTION] Hábito actualizado:', data.habit);
                    
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
                        console.log('[HABIT UPDATE] Actualizando hábito ID:', habitId);
                        console.log('[HABIT UPDATE] Estado is_completed:', data.habit.is_completed);
                        
                        // Encontrar y actualizar el hábito en la lista principal
                        const habitIndex = this.userHabits.findIndex(h => h.id === habitId);
                        if (habitIndex !== -1) {
                            console.log('[HABIT UPDATE] Hábito antes:', this.userHabits[habitIndex]);
                            this.userHabits[habitIndex] = { ...this.userHabits[habitIndex], ...data.habit };
                            console.log('[HABIT UPDATE] Hábito después:', this.userHabits[habitIndex]);
                        }
                        
                        // Actualizar también en activeHabits si existe
                        const activeIndex = this.activeHabits.findIndex(h => h.id === habitId);
                        if (activeIndex !== -1) {
                            console.log('[ACTIVE HABITS] Actualizando en activeHabits');
                            this.activeHabits[activeIndex] = { ...this.activeHabits[activeIndex], ...data.habit };
                        }
                        
                        // Actualizar también en completedHabits si existe
                        const completedIndex = this.completedHabits.findIndex(h => h.id === habitId);
                        if (completedIndex !== -1) {
                            console.log('[COMPLETED HABITS] Actualizando en completedHabits');
                            this.completedHabits[completedIndex] = { ...this.completedHabits[completedIndex], ...data.habit };
                        }
                        
                        // Actualizar el hábito expandido inmediatamente
                        if (this.expandedHabit && this.expandedHabit.id === habitId) {
                            console.log('[EXPANDED HABIT] Actualizando hábito expandido');
                            this.expandedHabit = { ...this.expandedHabit, ...data.habit };
                        }
                        
                        // Forzar reactividad de Alpine
                        this.$nextTick(() => {
                            console.log('[REACTIVITY] Estado final del hábito:', this.userHabits.find(h => h.id === habitId));
                        });
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
            console.log('Opening habit explorer...');
            this.showHabitExplorer = true;
            await this.loadAllHabits();
        },

        async loadAllHabits() {
            console.log('Loading all habits...');
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
