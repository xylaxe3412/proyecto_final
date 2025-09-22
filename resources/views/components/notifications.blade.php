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
