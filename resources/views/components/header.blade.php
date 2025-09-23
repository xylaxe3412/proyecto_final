<div class="bg-white dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-200 dark:border-white/20 animate-slide-down shadow-sm dark:shadow-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3 animate-bounce-subtle">
                <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg 
                            transform hover:scale-110 hover:rotate-6 transition-all duration-300 hover:shadow-2xl animate-pulse-glow">
                    <span class="text-lg font-black text-white">M</span>
                </div>
                
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white hover:text-motiveo-accent transition-colors duration-300 animate-text-glow"> <a href="/dashboard">MOTIVEO</a></h1>
            </div>
            <!-- User Level & XP -->
            <div class="flex items-center space-x-4 animate-fade-in-right">
                <div class="hidden sm:flex items-center space-x-3">
                    <!-- Botón de Página Externa -->
                    <a href="/pages/index.html" 
                       target="_blank"
                       class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold
                              hover:scale-105 hover:from-purple-600 hover:to-pink-600 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Página Web</span>
                    </a>
                    
                    <!-- Botón de Logros -->
                    <a href="{{ route('achievements.index') }}" 
                       class="bg-motiveo-accent text-white px-3 py-1 rounded-full text-sm font-bold
                              hover:scale-105 hover:bg-motiveo-accent/80 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-trophy"></i>
                        <span>Logros</span>
                        <span class="bg-white/20 px-2 rounded-full">
                            {{ Auth::user()->achievements->where('pivot.unlocked_at', '!=', null)->count() }}/{{ App\Models\Achievement::count() }}
                        </span>
                    </a>
                    
                    <!-- Contador de Racha -->
                    <div id="streak-counter" 
                         class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-full text-sm font-bold
                                hover:scale-105 transition-all duration-300 flex items-center gap-2 animate-pulse-slow">
                        <i class="fas fa-fire text-yellow-300"></i>
                        <span>Racha</span>
                        <span id="streak-number" class="bg-white/20 px-2 rounded-full">
                            {{ Auth::user()->getCurrentBestStreak() }}
                        </span>
                        <span class="text-xs opacity-75">días</span>
                    </div>
                    <!-- Nivel -->
                    <div class="bg-motiveo-warning text-white px-3 py-1 rounded-full text-sm font-bold 
                                hover:scale-105 hover:bg-motiveo-warning/80 transition-all duration-300 animate-wiggle-on-hover" 
                         x-text="`NIVEL ${userStats.level}`"
                         @mouseenter="$el.classList.add('animate-wiggle')"
                         @mouseleave="$el.classList.remove('animate-wiggle')">
                        NIVEL {{ auth()->user()->level ?? 1 }}
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 h-3 bg-gray-200 dark:bg-white/20 rounded-full overflow-hidden border border-gray-300 dark:border-white/30">
                            <div class="h-full bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full transition-all duration-1000 animate-progress-fill shadow-[0_0_8px_rgba(0,255,0,0.3)]" 
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
                        <button type="submit" class="text-gray-700 dark:text-white/90 text-sm hover:scale-105 transition-all duration-300 
                                                     bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-white/20">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
