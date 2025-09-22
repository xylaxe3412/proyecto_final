<div class="bg-white/10 backdrop-blur-md border-b border-white/20 animate-slide-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3 animate-bounce-subtle">
                <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg 
                            transform hover:scale-110 hover:rotate-6 transition-all duration-300 hover:shadow-2xl animate-pulse-glow">
                    <span class="text-lg font-black text-white">M</span>
                </div>
                <h1 class="text-2xl font-bold text-white hover:text-motiveo-accent transition-colors duration-300 animate-text-glow">MOTIVEO</h1>
            </div>
            <!-- User Level & XP -->
            <div class="flex items-center space-x-4 animate-fade-in-right">
                <div class="hidden sm:flex items-center space-x-3">
                    <div class="bg-motiveo-warning text-white px-3 py-1 rounded-full text-sm font-bold 
                                hover:scale-105 hover:bg-motiveo-warning/80 transition-all duration-300 animate-wiggle-on-hover" 
                         x-text="`NIVEL ${userStats.level}`"
                         @mouseenter="$el.classList.add('animate-wiggle')"
                         @mouseleave="$el.classList.remove('animate-wiggle')">
                        NIVEL {{ auth()->user()->level ?? 1 }}
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full transition-all duration-1000 animate-progress-fill" 
                                 :style="`width: ${userStats.progress}%`"
                                 style="width: {{ auth()->user()->getLevelProgress() ?? 0 }}%"></div>
                        </div>
                        <span class="text-white text-sm animate-number-count" x-text="`${userStats.xp}/${userStats.next_level_xp} XP`">
                            {{ auth()->user()->xp ?? 0 }}/{{ auth()->user()->getXpForNextLevel() ?? 100 }} XP
                        </span>
                    </div>
                </div>
                <!-- User Menu -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-motiveo-pink to-red-400 rounded-full flex items-center justify-center
                                hover:scale-110 transition-all duration-300 hover:shadow-lg animate-float">
                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white/80 hover:text-white text-sm hover:scale-105 transition-all duration-300 
                                                     hover:bg-white/10 px-3 py-1 rounded-lg">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
