<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motiveo - Construye Hábitos Poderosos</title>
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
                        Bienvenido a <span class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary bg-clip-text text-transparent">Motiveo</span>
                    </h1>
                    <p class="text-gray-600 text-lg font-medium mb-4">
                        Construye hábitos poderosos que cambien tu vida
                    </p>
                    <p class="text-gray-500 text-sm">
                        Únete a miles de personas que ya transformaron sus vidas con Motiveo
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 space-y-4">
                    <!-- Main CTA Button -->
                    <a 
                        href="{{ route('register') }}" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-gradient-to-r from-motiveo-primary to-motiveo-secondary hover:from-motiveo-secondary hover:to-motiveo-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-motiveo-primary transition-all duration-200 transform hover:scale-105"
                    >
                        Comenzar mi transformación 🚀
                    </a>

                    <!-- Secondary Button -->
                    <a 
                        href="{{ route('login') }}" 
                        class="w-full flex justify-center py-4 px-4 border-2 border-motiveo-primary rounded-xl text-base font-bold text-motiveo-primary bg-white hover:bg-motiveo-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-motiveo-primary transition-all duration-200"
                    >
                        Ya tengo una cuenta
                    </a>
                </div>

                <!-- Features List -->
                <div class="mt-8 space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-motiveo-success mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Seguimiento diario de hábitos</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-motiveo-success mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Estadísticas y progreso visual</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-motiveo-success mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Recordatorios inteligentes</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-motiveo-success mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Comunidad motivacional</span>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-black text-motiveo-primary">10K+</div>
                        <div class="text-xs text-gray-500 font-medium">Usuarios activos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-motiveo-secondary">250K+</div>
                        <div class="text-xs text-gray-500 font-medium">Hábitos completados</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-motiveo-success">95%</div>
                        <div class="text-xs text-gray-500 font-medium">Tasa de éxito</div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="text-center text-xs text-gray-500 mt-6">
                    Al registrarte, aceptas nuestros
                    <a href="#" class="text-motiveo-primary hover:underline font-medium">Términos de Servicio</a>
                    y
                    <a href="#" class="text-motiveo-primary hover:underline font-medium">Política de Privacidad</a>
                </div>

                <!-- Testimonial -->
                <div class="mt-8 p-4 bg-gradient-to-r from-motiveo-primary/5 to-motiveo-secondary/5 rounded-xl border border-motiveo-primary/10">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">M</span>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">María García</div>
                            <div class="text-xs text-gray-500">Perdió 15kg en 3 meses</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 italic">
                        "Motiveo cambió mi vida completamente. Ahora tengo hábitos saludables que realmente se mantienen."
                    </p>
                    <div class="flex mt-2">
                        <span class="text-motiveo-warning">⭐⭐⭐⭐⭐</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Habit Tracking Illustrations -->
        <div class="hidden lg:block lg:flex-1 relative overflow-hidden">
            <!-- Habit Streak Card -->
            <div class="absolute top-20 right-20 bg-white rounded-2xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-300 w-72">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Racha de Ejercicio</h3>
                    <div class="w-8 h-8 bg-gradient-to-r from-motiveo-success to-emerald-400 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-bold">🔥</span>
                    </div>
                </div>
                <div class="text-center mb-4">
                    <div class="text-4xl font-black text-motiveo-success mb-1">21</div>
                    <div class="text-sm text-gray-600 font-medium">días consecutivos</div>
                </div>
                <div class="flex justify-center space-x-1 mb-4">
                    <div class="w-6 h-6 bg-motiveo-success rounded-full"></div>
                    <div class="w-6 h-6 bg-motiveo-success rounded-full"></div>
                    <div class="w-6 h-6 bg-motiveo-success rounded-full"></div>
                    <div class="w-6 h-6 bg-motiveo-success rounded-full"></div>
                    <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                </div>
                <div class="text-center text-xs text-gray-500">¡Solo 9 días para tu récord personal!</div>
            </div>

            <!-- Weekly Progress Chart -->
            <div class="absolute top-32 right-96 bg-white rounded-2xl p-4 shadow-2xl transform -rotate-2 hover:rotate-0 transition-all duration-300">
                <h4 class="text-sm font-bold text-gray-800 mb-3">Progreso Semanal</h4>
                <div class="flex items-end space-x-2 h-24">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-16 bg-gradient-to-t from-motiveo-primary to-motiveo-secondary rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">L</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-20 bg-gradient-to-t from-motiveo-primary to-motiveo-secondary rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">M</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-12 bg-gradient-to-t from-motiveo-primary to-motiveo-secondary rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">M</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-18 bg-gradient-to-t from-motiveo-primary to-motiveo-secondary rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">J</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-14 bg-gradient-to-t from-motiveo-primary to-motiveo-secondary rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">V</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-22 bg-gradient-to-t from-motiveo-success to-emerald-400 rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">S</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-10 bg-gray-200 rounded-t"></div>
                        <span class="text-xs text-gray-500 mt-1">D</span>
                    </div>
                </div>
            </div>

            <!-- Habits Dashboard -->
            <div class="absolute bottom-20 right-16 bg-white rounded-2xl p-6 shadow-2xl transform rotate-1 hover:rotate-0 transition-all duration-300 w-80">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Mis Hábitos de Hoy</h3>
                <div class="space-y-3">
                    <!-- Habit 1 -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-motiveo-success/10 to-emerald-50 rounded-xl border border-motiveo-success/20">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-800">Ejercicio matutino</span>
                        </div>
                        <span class="text-xs font-bold text-motiveo-success">✓ Completado</span>
                    </div>

                    <!-- Habit 2 -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-motiveo-primary/10 to-indigo-50 rounded-xl border border-motiveo-primary/20">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-motiveo-primary rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-xs">📚</span>
                            </div>
                            <span class="font-medium text-gray-800">Leer 30 minutos</span>
                        </div>
                        <div class="text-xs font-bold text-motiveo-primary">En progreso</div>
                    </div>

                    <!-- Habit 3 -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-motiveo-warning/10 to-yellow-50 rounded-xl border border-motiveo-warning/20">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-motiveo-warning rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-xs">🧘</span>
                            </div>
                            <span class="font-medium text-gray-800">Meditar</span>
                        </div>
                        <div class="text-xs font-bold text-motiveo-warning">Pendiente</div>
                    </div>

                    <!-- Habit 4 -->
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-motiveo-pink/10 to-pink-50 rounded-xl border border-motiveo-pink/20">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-motiveo-pink rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-xs">💧</span>
                            </div>
                            <span class="font-medium text-gray-800">Beber agua</span>
                        </div>
                        <div class="text-xs font-bold text-motiveo-pink">6/8 vasos</div>
                    </div>
                </div>

                <!-- Progress Summary -->
                <div class="mt-4 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progreso del día</span>
                        <span class="text-sm font-bold text-motiveo-primary">75%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-motiveo-primary to-motiveo-secondary h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>