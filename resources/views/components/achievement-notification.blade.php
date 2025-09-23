<div 
    x-data="{ 
        show: false,
        achievement: null,
        showNotification(achievement) {
            this.achievement = achievement;
            this.show = true;
            // Reproducir sonido
            window.playAchievementSound();
            // Ocultar después de 5 segundos
            setTimeout(() => this.show = false, 5000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    @achievement-unlocked.window="showNotification($event.detail)"
    class="fixed top-4 right-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm w-full z-50"
>
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                <i x-bind:class="'fas fa-' + (achievement?.icon || 'trophy')" class="text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
        </div>
        <div class="ml-3 w-0 flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'¡Logro Desbloqueado!'"></p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="achievement?.name"></p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="achievement?.description"></p>
            <p class="mt-1 text-xs font-medium text-green-600 dark:text-green-400" x-text="'+ ' + achievement?.xp_reward + ' XP'"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button
                @click="show = false"
                class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <span class="sr-only">Cerrar</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>