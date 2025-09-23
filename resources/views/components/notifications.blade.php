<!-- Notificaciones Generales -->
<div x-show="notification.show" 
     x-transition:enter="transition ease-out duration-500 transform"
     x-transition:enter-start="opacity-0 transform translate-y-2 scale-95 rotate-1"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100 rotate-0"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100 rotate-0"
     x-transition:leave-end="opacity-0 transform translate-y-2 scale-95 rotate-1"
     class="fixed top-4 right-4 bg-motiveo-success text-white px-6 py-3 rounded-lg shadow-2xl z-50 
            animate-bounce-gentle border-2 border-white/20 backdrop-blur-sm"
     x-text="notification.message">
</div>

<!-- Notificación de Racha en Peligro -->
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
        <button @click="closeStreakNotification('warning')" 
                class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
            ×
        </button>
    </div>
</div>

<!-- Notificación de Racha Iniciada -->
<div id="streak-started" 
     x-show="streakNotifications.started.show"
     x-transition:enter="transition ease-out duration-600 transform"
     x-transition:enter-start="opacity-0 transform translate-y-full scale-90 rotate-2"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100 rotate-0"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100 rotate-0"
     x-transition:leave-end="opacity-0 transform translate-y-full scale-90 rotate-2"
     class="fixed top-32 right-4 bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
            border-2 border-orange-300/50 backdrop-blur-sm max-w-sm animate-pulse">
    <div class="flex items-center space-x-3">
        <div class="text-3xl animate-bounce">
            🔥
        </div>
        <div>
            <div class="font-bold text-lg">¡Racha Iniciada! 🚀</div>
            <div class="text-sm opacity-90" x-text="streakNotifications.started.message"></div>
            <div class="text-xs opacity-75 mt-1">¡Comienza tu jornada diaria!</div>
        </div>
        <button @click="closeStreakNotification('started')" 
                class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
            ×
        </button>
    </div>
</div>

<!-- Notificación de Racha Salvada -->
<div id="streak-saved" 
     x-show="streakNotifications.saved.show"
     x-transition:enter="transition ease-out duration-500 transform"
     x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
     x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
     x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
     class="fixed top-20 right-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
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
        <button @click="closeStreakNotification('saved')" 
                class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
            ×
        </button>
    </div>
</div>

<!-- Notificación de Nueva Racha Personal -->
<div id="streak-record" 
     x-show="streakNotifications.record.show"
     x-transition:enter="transition ease-out duration-500 transform"
     x-transition:enter-start="opacity-0 transform translate-y-full scale-95"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform translate-y-full scale-95"
     class="fixed bottom-4 right-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 
            border-2 border-purple-300/50 backdrop-blur-sm max-w-sm animate-pulse">
    <div class="flex items-center space-x-3">
        <div class="text-3xl animate-spin-slow">
            <i class="fas fa-crown text-yellow-300"></i>
        </div>
        <div>
            <div class="font-bold text-lg">¡Nuevo Récord! 👑</div>
            <div class="text-sm opacity-90" x-text="streakNotifications.record.message"></div>
            <div class="text-xs opacity-75 mt-1">¡Increíble dedicación!</div>
        </div>
        <button @click="closeStreakNotification('record')" 
                class="text-white/70 hover:text-white text-xl font-bold leading-none ml-2">
            ×
        </button>
    </div>
</div>

<style>
.animate-spin-slow {
    animation: spin 3s linear infinite;
}

.animate-pulse-slow {
    animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
