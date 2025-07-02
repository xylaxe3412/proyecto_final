<<<<<<< HEAD
@extends('layouts.app')

@section('content')
<body>
<div class="app-container">
    <!-- Header con logo y nivel -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🎯</div>
            <h1>MOTIVEO</h1>
        </div>
        <div class="user-level">
            <div class="level-badge">NIVEL 5</div>
            <div class="xp-bar">
                <div class="xp-fill" style="width: 85%"></div>
            </div>
            <span>850/1000 XP</span>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Panel izquierdo - Mis Hábitos -->
        <div class="left-panel">
            <div class="habits-section">
                <div class="section-header">
                    <div class="section-icon">🏆</div>
                    <h2>Mis Hábitos</h2>
                </div>
                
                <div class="habit-item health">
                    <div class="habit-icon">🏥</div>
                    <div class="habit-info">
                        <h3>Salud</h3>
                        <div class="habit-streak">🔥 7 días</div>
                    </div>
                </div>

                <div class="habit-item productivity">
                    <div class="habit-icon">🏢</div>
                    <div class="habit-info">
                        <h3>Productividad</h3>
                        <div class="habit-streak">🔥 12 días</div>
                    </div>
                </div>

                <div class="habit-item wellness">
                    <div class="habit-icon">😊</div>
                    <div class="habit-info">
                        <h3>Bienestar</h3>
                        <div class="habit-streak">⭐ 45 días</div>
                    </div>
                </div>

                <div class="habit-item learning">
                    <div class="habit-icon">📚</div>
                    <div class="habit-info">
                        <h3>Aprendizaje</h3>
                        <div class="habit-streak">🔥 15 días</div>
                    </div>
                </div>

                <button class="create-habit-btn" onclick="createNewHabit()">
                    ➕ Crear Nuevo Hábito
                </button>
            </div>
        </div>

        <!-- Panel central - Actividades -->
        <div class="center-panel">
            <div class="activities-grid">
                <div class="activity-card flash-card">
                    <div class="activity-header">
                        <div class="activity-icon">🎯</div>
                        <h3>Meta Flash</h3>
                        <div class="activity-badge">🍯</div>
                    </div>
                    <p>Completar 3 tareas pendientes importantes del día</p>
                    <div class="activity-footer">
                        <div class="days-counter">🔥 5 días</div>
                        <button class="complete-btn partial" onclick="completeActivity(this, 'Meta Flash')">
                            Completar 2/3
                        </button>
                    </div>
                </div>

                <div class="activity-card hydration-card">
                    <div class="activity-header">
                        <div class="activity-icon">💧</div>
                        <h3>Hidratación Express</h3>
                    </div>
                    <p>Tomar 1 vaso de agua ahora y mantenerte hidratado</p>
                    <div class="activity-footer">
                        <div class="days-counter">⭐ 28 días</div>
                        <button class="complete-btn active" onclick="completeActivity(this, 'Hidratación Express')">
                            Tomar agua 5/6
                        </button>
                    </div>
                </div>

                <div class="activity-card destress-card">
                    <div class="activity-header">
                        <div class="activity-icon">🧘</div>
                        <h3>Desestrés Rápido</h3>
                    </div>
                    <p>Respiración 4-7-8 durante 2 minutos para relajarte</p>
                    <div class="activity-footer">
                        <div class="days-counter">🔥 9 días</div>
                        <button class="complete-btn incomplete" onclick="completeActivity(this, 'Desestrés Rápido')">
                            Completar 0/5
                        </button>
                    </div>
                </div>

                <div class="activity-card learning-card">
                    <div class="activity-header">
                        <div class="activity-icon">📚</div>
                        <h3>Micro-aprendizaje</h3>
                    </div>
                    <p>Leer un artículo profesional de tu área de interés</p>
                    <div class="activity-footer">
                        <div class="days-counter">🔥 16 días</div>
                        <button class="complete-btn active" onclick="completeActivity(this, 'Micro-aprendizaje')">
                            Leer 3/7
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Completados y Progreso -->
        <div class="right-panel">
            <div class="completed-section">
                <div class="section-header">
                    <div class="section-icon">✅</div>
                    <h2>Completados Hoy</h2>
                </div>
                
                <div class="completed-item">
                    <div class="completed-time">08:15</div>
                    <div class="completed-text">🧘 Meditación (Salud)</div>
                </div>
                <div class="completed-item">
                    <div class="completed-time">10:30</div>
                    <div class="completed-text">🏢 Revisión metas (Productividad)</div>
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

            <div class="progress-section">
                <div class="section-header">
                    <div class="section-icon">🏢</div>
                    <h2>Progreso por Categoría</h2>
                </div>

                <div class="progress-item">
                    <div class="progress-info">
                        <div class="category-icon health-icon">🏥</div>
                        <span>Salud</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill health-progress" style="width: 90%"></div>
                    </div>
                    <span class="progress-percentage">90%</span>
                </div>

                <div class="progress-item">
                    <div class="progress-info">
                        <div class="category-icon productivity-icon">🏢</div>
                        <span>Productividad</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill productivity-progress" style="width: 75%"></div>
                    </div>
                    <span class="progress-percentage">75%</span>
                </div>

                <div class="progress-item">
                    <div class="progress-info">
                        <div class="category-icon wellness-icon">😊</div>
                        <span>Bienestar</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill wellness-progress" style="width: 95%"></div>
                    </div>
                    <span class="progress-percentage">95%</span>
                </div>

                <div class="progress-item">
                    <div class="progress-info">
                        <div class="category-icon learning-icon">📚</div>
                        <span>Aprendizaje</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill learning-progress" style="width: 60%"></div>
                    </div>
                    <span class="progress-percentage">60%</span>
                </div>

                <div class="achievements-section">
                    <div class="section-title">🏆 Próximos Logros:</div>
                    <div class="achievement-item">
                        <span class="achievement-icon">👑</span>
                        <span class="achievement-text">Rey Hidratación (50 días) - 95%</span>
                    </div>
                    <div class="achievement-item">
                        <span class="achievement-icon">🌙</span>
                        <span class="achievement-text">Sabio Nocturno (21 días) - 33%</span>
                    </div>
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
>>>>>>> 67eb95f44ae58db7ce3a1ff1ee249f01ccb1cbc7
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        min-height: 100vh;
        color: white;
    }

    .app-container {
        min-height: 100vh;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        font-size: 24px;
        background: linear-gradient(45deg, #ff6b6b, #ffa500);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo h1 {
        font-size: 24px;
        font-weight: bold;
        background: linear-gradient(45deg, #ff6b6b, #ffa500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .user-level {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .level-badge {
        background: linear-gradient(45deg, #ffa500, #ff8c00);
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 12px;
    }

    .xp-bar {
        width: 200px;
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .xp-fill {
        height: 100%;
        background: linear-gradient(90deg, #00d4aa, #00b894);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        gap: 20px;
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .left-panel, .right-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .habits-section, .completed-section, .progress-section {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .section-icon {
        font-size: 20px;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
    }

    .habit-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .habit-item:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.1);
    }

    .habit-icon {
        font-size: 20px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .health .habit-icon { background: rgba(220, 53, 69, 0.2); }
    .productivity .habit-icon { background: rgba(0, 123, 255, 0.2); }
    .wellness .habit-icon { background: rgba(111, 66, 193, 0.2); }
    .learning .habit-icon { background: rgba(255, 193, 7, 0.2); }

    .habit-info h3 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .habit-streak {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
    }

    .create-habit-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .create-habit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .activities-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .activity-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .activity-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff6b6b, #ffa500);
    }

    .activity-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .flash-card::before { background: linear-gradient(90deg, #ff6b6b, #ffa500); }
    .hydration-card::before { background: linear-gradient(90deg, #00d4aa, #00b894); }
    .destress-card::before { background: linear-gradient(90deg, #a855f7, #8b5cf6); }
    .learning-card::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }

    .activity-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        position: relative;
    }

    .activity-icon {
        font-size: 24px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.1);
    }

    .activity-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 16px;
    }

    .activity-header h3 {
        font-size: 16px;
        font-weight: 600;
    }

    .activity-card p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 16px;
    }

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .days-counter {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
    }

    .complete-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .complete-btn.active {
        background: linear-gradient(45deg, #00d4aa, #00b894);
        color: white;
    }

    .complete-btn.partial {
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        color: white;
    }

    .complete-btn.incomplete {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
    }

    .completed-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .completed-time {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        min-width: 40px;
    }

    .completed-text {
        font-size: 14px;
    }

    .progress-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .progress-info {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 120px;
    }

    .category-icon {
        font-size: 16px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .health-icon { background: rgba(220, 53, 69, 0.2); }
    .productivity-icon { background: rgba(0, 123, 255, 0.2); }
    .wellness-icon { background: rgba(111, 66, 193, 0.2); }
    .learning-icon { background: rgba(255, 193, 7, 0.2); }

    .progress-bar {
        flex: 1;
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .health-progress { background: linear-gradient(90deg, #dc3545, #c82333); }
    .productivity-progress { background: linear-gradient(90deg, #007bff, #0056b3); }
    .wellness-progress { background: linear-gradient(90deg, #6f42c1, #5a32a3); }
    .learning-progress { background: linear-gradient(90deg, #ffc107, #e0a800); }

    .progress-percentage {
        font-size: 12px;
        font-weight: 600;
        min-width: 35px;
        text-align: right;
    }

    .achievements-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .achievement-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 8px;
    }

    .achievement-icon {
        font-size: 14px;
    }

    @media (max-width: 1200px) {
        .dashboard-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .activities-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function completeActivity(button, activityName) {
        // Simular completar actividad
        button.style.background = 'linear-gradient(45deg, #059669, #047857)';
        button.textContent = '¡Completado! ✓';
        button.disabled = true;
        
        // Agregar a completados
        addToCompleted(activityName);
        
        // Actualizar XP
        updateXP(50);
        
        // Animación de celebración
        const card = button.closest('.activity-card');
        card.style.transform = 'translateY(-8px) scale(1.02)';
        setTimeout(() => {
            card.style.transform = 'translateY(-2px)';
        }, 300);
    }

    function addToCompleted(activityName) {
        const completedSection = document.querySelector('.completed-section');
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
                card.style.transform = 'translateY(-6px) scale(1.01)';
                setTimeout(() => {
                    card.style.transform = 'translateY(-2px)';
                }, 400);
            }, index * 150);
        });
    }, 15000);

    // Efectos de hover dinámicos
    document.querySelectorAll('.activity-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px)';
        });
    });
</script>
</body>
@endsection
=======
</x-app-layout>
>>>>>>> 67eb95f44ae58db7ce3a1ff1ee249f01ccb1cbc7
