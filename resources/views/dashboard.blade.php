
@extends('layouts.app')

@section('content')
<body>
<div class="header">
<div class="logo">
<div class="logo-icon">🎯</div>
            MOTIVEO
        </div>
<div class="user-level">
<div class="level-badge">NIVEL 5</div>
<div class="xp-bar">
<div class="xp-fill"></div>
</div>
<span style="font-size: 12px; color: #8b949e;">850/1000 XP</span>
</div>
</div>
<div class="container">
<!-- Sidebar: Mis Hábitos -->
<div class="sidebar">
<h3>🏆 Mis Hábitos</h3>
<div class="habit-item">
<div class="habit-info">
<div class="habit-icon health">🏥</div>
<div class="habit-details">
<h4>Salud</h4>
<div class="habit-streak">🔥 <span class="streak-fire">{{ $habitos[0]->dias_racha ?? "0" }} días</span></div>
</div>
</div>
</div>
<div class="habit-item">
<div class="habit-info">
<div class="habit-icon productivity">📊</div>
<div class="habit-details">
<h4>Productividad</h4>
<div class="habit-streak">🔥 <span class="streak-fire">{{ $habitos[1]->dias_racha ?? "0" }} días</span></div>
</div>
</div>
</div>
<div class="habit-item">
<div class="habit-info">
<div class="habit-icon wellness">😌</div>
<div class="habit-details">
<h4>Bienestar</h4>
<div class="habit-streak">⭐ <span class="streak-fire">{{ $habitos[2]->dias_racha ?? "0" }} días</span></div>
</div>
</div>
</div>
<div class="habit-item">
<div class="habit-info">
<div class="habit-icon learning">📚</div>
<div class="habit-details">
<h4>Aprendizaje</h4>
<div class="habit-streak">🔥 <span class="streak-fire">{{ $habitos[3]->dias_racha ?? "0" }} días</span></div>
</div>
</div>
</div>
<button class="create-habit-btn" onclick="createNewHabit()">
<span>➕</span>
                Crear Nuevo Hábito
            </button>
</div>
<!-- Main Content: Actividades Recomendadas -->
<div class="main-content">
<div class="activity-card pulse">
<div class="celebration">🎉</div>
<div class="activity-header">
<div class="activity-title">
<div class="activity-icon health">🎯</div>
                        Meta Flash
                    </div>
</div>
<div class="activity-description">
                    Completar 3 tareas pendientes importantes del día
                </div>
<div class="activity-stats">
<div class="days-counter">🔥 5 días</div>
<button class="complete-btn" onclick="completeActivity(this, 'Meta Flash')">
                        Completar 2/3
                    </button>
</div>
</div>
<div class="activity-card">
<div class="activity-header">
<div class="activity-title">
<div class="activity-icon wellness">💧</div>
                        Hidratación Express
                    </div>
</div>
<div class="activity-description">
                    Tomar 1 vaso de agua ahora y mantenerte hidratado
                </div>
<div class="activity-stats">
<div class="days-counter">⭐ 28 días</div>
<button class="complete-btn" onclick="completeActivity(this, 'Hidratación Express')">
                        Tomar agua 5/8
                    </button>
</div>
</div>
<div class="activity-card">
<div class="activity-header">
<div class="activity-title">
<div class="activity-icon wellness">🧘</div>
                        Desestrés Rápido
                    </div>
</div>
<div class="activity-description">
                    Respiración 4-7-8 durante 2 minutos para relajarte
                </div>
<div class="activity-stats">
<div class="days-counter">🔥 9 días</div>
<button class="complete-btn" onclick="completeActivity(this, 'Desestrés Rápido')">
                        Completar 0/5
                    </button>
</div>
</div>
<div class="activity-card">
<div class="activity-header">
<div class="activity-title">
<div class="activity-icon learning">📚</div>
                        Micro-aprendizaje
                    </div>
</div>
<div class="activity-description">
                    Leer un artículo profesional de tu área de interés
                </div>
<div class="activity-stats">
<div class="days-counter">🔥 16 días</div>
<button class="complete-btn" onclick="completeActivity(this, 'Micro-aprendizaje')">
                        Leer 3/7
                    </button>
</div>
</div>
</div>
<!-- Right Panel: Progreso y Completados -->
<div class="right-panel">
<!-- Hábitos Completados Hoy -->
<div class="panel-section">
<div class="panel-title">
                    ✅ Completados Hoy
                </div>
<div class="completed-item">
<div class="completed-time">08:15</div>
<div class="completed-text">🧘 Meditación (Salud)</div>
</div>
<div class="completed-item">
<div class="completed-time">10:30</div>
<div class="completed-text">📝 Revisión metas (Productividad)</div>
</div>
<div class="completed-item">
<div class="completed-time">13:20</div>
<div class="completed-text">💧 Hidratación (Bienestar)</div>
</div>
<div class="completed-item">
<div class="completed-time">16:45</div>
<div class="completed-text">🌍 Inglés (Aprendizaje)</div>
</div>
</div>
<!-- Progreso por Categoría -->
<div class="panel-section">
<div class="panel-title">
                    📊 Progreso por Categoría
                </div>
<div class="progress-item">
<div class="progress-info">
<div class="habit-icon health" style="width: 24px; height: 24px; font-size: 12px;">🏥</div>
<span style="font-size: 13px;">Salud</span>
</div>
<div style="display: flex; align-items: center; gap: 8px;">
<div class="progress-bar">
<div class="progress-fill health-fill" style="width: 90%;"></div>
</div>
<div class="progress-percentage">90%</div>
</div>
</div>
<div class="progress-item">
<div class="progress-info">
<div class="habit-icon productivity" style="width: 24px; height: 24px; font-size: 12px;">📊</div>
<span style="font-size: 13px;">Productividad</span>
</div>
<div style="display: flex; align-items: center; gap: 8px;">
<div class="progress-bar">
<div class="progress-fill productivity-fill" style="width: 75%;"></div>
</div>
<div class="progress-percentage">75%</div>
</div>
</div>
<div class="progress-item">
<div class="progress-info">
<div class="habit-icon wellness" style="width: 24px; height: 24px; font-size: 12px;">😌</div>
<span style="font-size: 13px;">Bienestar</span>
</div>
<div style="display: flex; align-items: center; gap: 8px;">
<div class="progress-bar">
<div class="progress-fill wellness-fill" style="width: 95%;"></div>
</div>
<div class="progress-percentage">95%</div>
</div>
</div>
<div class="progress-item">
<div class="progress-info">
<div class="habit-icon learning" style="width: 24px; height: 24px; font-size: 12px;">📚</div>
<span style="font-size: 13px;">Aprendizaje</span>
</div>
<div style="display: flex; align-items: center; gap: 8px;">
<div class="progress-bar">
<div class="progress-fill learning-fill" style="width: 60%;"></div>
</div>
<div class="progress-percentage">60%</div>
</div>
</div>
<div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #21262d;">
<div style="font-size: 12px; color: #8b949e; margin-bottom: 8px;">🏆 Próximos Logros:</div>
<div style="font-size: 11px; color: #10b981;">🔓 Rey Hidratación (50 días) - 95%</div>
<div style="font-size: 11px; color: #fbbf24;">🔓 Sabio Nocturno (21 días) - 33%</div>
</div>
</div>
</div>
</div>
<script>
        function completeActivity(button, activityName) {
            // Simular completar actividad
            button.style.background = '#059669';
            button.textContent = '¡Completado! ✓';
            button.disabled = true;
            
            // Agregar a completados
            addToCompleted(activityName);
            
            // Actualizar XP
            updateXP(50);
            
            // Animación de celebración
            const card = button.closest('.activity-card');
            card.style.transform = 'scale(1.02)';
            setTimeout(() => {
                card.style.transform = 'translateY(-2px)';
            }, 200);
        }

        function addToCompleted(activityName) {
            const completedSection = document.querySelector('.panel-section');
            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            const newItem = document.createElement('div');
            newItem.className = 'completed-item';
            newItem.innerHTML = `
                <div class="completed-time">${timeString}</div>
                <div class="completed-text">✨ ${activityName}</div>
            `;
            
            completedSection.appendChild(newItem);
        }

        function updateXP(points) {
            const xpFill = document.querySelector('.xp-fill');
            const currentWidth = parseInt(xpFill.style.width || '85');
            const newWidth = Math.min(100, currentWidth + 5);
            xpFill.style.width = newWidth + '%';
            
            // Actualizar texto XP
            const xpText = document.querySelector('.user-level span');
            let currentXP = parseInt(xpText.textContent.split('/')[0]);
            currentXP += points;
            xpText.textContent = `${currentXP}/1000 XP`;
        }

        function createNewHabit() {
            alert('¡Función para crear nuevo hábito! En Laravel 12 se abrirá un modal o nueva página.');
        }

        // Animaciones periódicas
        setInterval(() => {
            const cards = document.querySelectorAll('.activity-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.transform = 'translateY(-2px) scale(1.01)';
                    setTimeout(() => {
                        card.style.transform = 'translateY(-2px)';
                    }, 300);
                }, index * 100);
            });
        }, 10000);
    </script>
</body>
@endsection
