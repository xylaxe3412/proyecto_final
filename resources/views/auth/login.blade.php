<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motiveo - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'motiveo-primary': '#6366f1',
                        'motiveo-secondary': '#8b5cf6',
                        'motiveo-accent': '#06b6d4',
                        'motiveo-success': '#10b981',
                        'motiveo-warning': '#f59e0b',
                        'motiveo-pink': '#ec4899',
                        'motiveo-dark': '#1e1b4b'
                    },
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-motiveo-dark via-purple-900 to-indigo-900 font-display overflow-hidden">
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-md w-full space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-2xl flex items-center justify-center shadow-xl">
                                <span class="text-2xl font-black text-white">M</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-motiveo-success rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h1 class="text-4xl font-black text-gray-900 mb-2">
                        Bienvenido de vuelta
                    </h1>
                    <p class="text-gray-600 text-lg font-medium mb-4">
                        Continúa construyendo tus hábitos poderosos
                    </p>
                    <p class="text-gray-500 text-sm">
                        Accede a tu dashboard de <span class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary bg-clip-text text-transparent font-bold">Motiveo</span>
                    </p>
                </div>

                <!-- Form -->
                <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    @if(session('status'))
                        <div class="bg-motiveo-success/10 border border-motiveo-success/20 text-motiveo-success px-4 py-3 rounded-xl">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
                            <ul class="text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary transition-all duration-200" 
                            placeholder="tu@email.com"
                            value="{{ old('email') }}"
                        >
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Contraseña</label>
                            <a href="{{ route('password.request') }}" class="text-sm text-motiveo-primary hover:underline font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-motiveo-primary transition-all duration-200" 
                            placeholder="Tu contraseña"
                        >
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-motiveo-primary focus:ring-motiveo-primary border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 font-medium">
                                Recordarme
                            </label>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-gradient-to-r from-motiveo-primary to-motiveo-secondary hover:from-motiveo-secondary hover:to-motiveo-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-motiveo-primary transition-all duration-200 transform hover:scale-105"
                    >
                        Iniciar Sesión 🚀
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">o continúa con</span>
                        </div>
                    </div>

                    <!-- Google Sign Up -->
                        <a href="{{ route('google.login') }}"
                            class="flex items-center justify-center w-full border border-gray-300 py-2 rounded-md mb-4 bg-white hover:bg-gray-50">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 mr-2">
                            Continuar con Google
                        </a>

                    <!-- Quick Stats -->
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gradient-to-r from-motiveo-success/10 to-emerald-50 rounded-xl border border-motiveo-success/20">
                            <div class="text-2xl font-black text-motiveo-success">12K+</div>
                            <div class="text-xs text-gray-600 font-medium">Usuarios activos</div>
                        </div>
                        <div class="text-center p-3 bg-gradient-to-r from-motiveo-primary/10 to-indigo-50 rounded-xl border border-motiveo-primary/20">
                            <div class="text-2xl font-black text-motiveo-primary">85%</div>
                            <div class="text-xs text-gray-600 font-medium">Éxito en hábitos</div>
                        </div>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        ¿Nuevo en Motiveo?
                        <a href="{{ route('register') }}" class="text-motiveo-primary hover:underline font-semibold">Crear cuenta gratis</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Success Stories & Statistics -->
        <div class="hidden lg:block lg:flex-1 relative overflow-hidden">
            <!-- User Success Story -->
            <div class="absolute top-20 right-20 bg-white rounded-2xl p-6 shadow-2xl transform rotate-2 hover:rotate-0 transition-all duration-300 w-80">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">MJ</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">María José</h3>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Racha actual:</span>
                            <span class="text-sm font-bold text-motiveo-success">94 días</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 italic">
                        "Motiveo cambió mi vida. He mantenido mis hábitos de ejercicio y lectura por más de 3 meses consecutivos. ¡Nunca pensé que sería posible!"
                    </p>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>⭐⭐⭐⭐⭐</span>
                    <span>Hace 2 días</span>
                </div>
            </div>

            <!-- Achievement Unlocked -->
            <div class="absolute top-32 right-96 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl p-4 shadow-2xl transform -rotate-3 hover:rotate-0 transition-all duration-300">
                <div class="text-center text-white">
                    <div class="text-2xl mb-2">🏆</div>
                    <div class="font-bold text-sm">¡Logro Desbloqueado!</div>
                    <div class="text-xs opacity-90">Maestro de Hábitos</div>
                    <div class="text-xs opacity-80 mt-1">30 días consecutivos</div>
                </div>
            </div>

            <!-- Habits Completion Chart -->
            <div class="absolute bottom-20 right-16 bg-white rounded-2xl p-6 shadow-2xl transform rotate-1 hover:rotate-0 transition-all duration-300 w-80">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Estadísticas de Hoy</h3>
                
                <!-- Circular Progress -->
                <div class="flex justify-center mb-6">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 128 128">
                            <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="64" cy="64" r="56" stroke="url(#gradient)" stroke-width="8" fill="none" stroke-linecap="round" stroke-dasharray="351.86" stroke-dashoffset="87.97"/>
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#6366f1"/>
                                    <stop offset="100%" style="stop-color:#8b5cf6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-black text-motiveo-primary">75%</div>
                                <div class="text-xs text-gray-600">Completado</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Stats -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Hábitos completados</span>
                        <span class="font-bold text-motiveo-success">3/4</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Racha actual</span>
                        <span class="font-bold text-motiveo-primary">21 días</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Mejor racha</span>
                        <span class="font-bold text-motiveo-warning">47 días</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nivel actual</span>
                        <span class="font-bold text-motiveo-pink">Experto</span>
                    </div>
                </div>

                <!-- Quick Action -->
                <div class="mt-4 p-3 bg-gradient-to-r from-motiveo-primary/10 to-indigo-50 rounded-xl border border-motiveo-primary/20">
                    <div class="text-center">
                        <div class="text-sm font-bold text-motiveo-primary mb-1">¡Estás muy cerca!</div>
                        <div class="text-xs text-gray-600">Solo falta completar "Meditar 10 min"</div>
                    </div>
                </div>
            </div>

            <!-- Floating Motivational Messages -->
            <div class="absolute top-1/2 left-10 bg-white rounded-full p-4 shadow-lg opacity-90 animate-bounce">
                <span class="text-2xl">💪</span>
            </div>
            <div class="absolute top-1/4 left-1/3 bg-white rounded-full p-3 shadow-lg opacity-80 animate-pulse">
                <span class="text-xl">🎯</span>
            </div>
            <div class="absolute bottom-1/3 left-20 bg-white rounded-full p-3 shadow-lg opacity-70 animate-ping">
                <span class="text-xl">🔥</span>
            </div>
            <div class="absolute top-3/4 left-1/2 bg-white rounded-full p-2 shadow-lg opacity-60 animate-bounce">
                <span class="text-lg">⭐</span>
            </div>
        </div>
    </div>
</body>
</html>