<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}" x-effect="localStorage.setItem('darkMode', darkMode); if(darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Additional Styles -->
        @stack('styles')
        
        <!-- Additional Scripts -->
        @stack('scripts')
    </head>
    <body class="font-sans antialiased">
        <!-- Botón flotante para alternar modo claro/oscuro -->
        <button @click="darkMode = !darkMode" class="fixed bottom-6 right-6 z-50 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow-lg rounded-full p-3 transition-colors duration-300 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">
            <span x-show="!darkMode" class="text-gray-800"><i class="fas fa-moon"></i></span>
            <span x-show="darkMode" class="text-yellow-400"><i class="fas fa-sun"></i></span>
        </button>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Achievement Notification Component -->
            <x-achievement-notification />

            <!-- Achievement Sound -->
            <audio id="achievementSound" preload="auto">
                <source src="{{ asset('sounds/achievement.mp3') }}" type="audio/mpeg">
            </audio>
        </div>

        <script>
            // Función para reproducir el sonido de logro
            window.playAchievementSound = function() {
                const sound = document.getElementById('achievementSound');
                sound.currentTime = 0;
                sound.play();
            };

            // Configuración de Echo para los eventos de logros
            window.Echo.private('achievements.' + '{{ auth()->id() }}')
                .listen('.achievement.unlocked', (event) => {
                    window.dispatchEvent(new CustomEvent('achievement-unlocked', {
                        detail: event.achievement
                    }));
                });
        </script>

        @stack('scripts')
    </body>
</html>
