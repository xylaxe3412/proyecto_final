<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Hábito - Motiveo</title>
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
<body class="h-full bg-gradient-to-br from-motiveo-dark via-purple-900 to-indigo-900 font-display">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-motiveo-primary to-motiveo-secondary rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-lg font-black text-white">M</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">MOTIVEO</h1>
                </div>

                <!-- Progress indicator -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-motiveo-success rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">1</span>
                        </div>
                        <div class="w-8 bg-white/30 h-0.5 rounded-full"></div>
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full">
            <!-- Card Container -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-motiveo-warning to-motiveo-pink rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-2">Configura tu Hábito</h2>
                    <p class="text-white/70 font-medium">Define tu objetivo y comienza tu transformación</p>
                </div>

                <!-- Form -->
                <form action="{{ route('formulario_habito.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Nombre Field -->
                    <div>
                        <label class="block text-white font-semibold mb-2 flex items-center">
                            <span class="mr-2">👤</span>
                            Tu Nombre
                        </label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-transparent backdrop-blur-sm" 
                            placeholder="¿Cómo te llamas?"
                            required>
                        @error('nombre') 
                            <p class="text-red-300 text-sm mt-2 flex items-center">
                                <span class="mr-1">⚠️</span>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Hábito Field -->
                    <div>
                        <label class="block text-white font-semibold mb-2 flex items-center">
                            <span class="mr-2">🚀</span>
                            ¿Qué hábito quieres alcanzar?
                        </label>
                        <input type="text" name="habito" value="{{ old('habito') }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-transparent backdrop-blur-sm"
                            placeholder="Ej: Hacer ejercicio, leer, meditar..."
                            required>
                        @error('habito') 
                            <p class="text-red-300 text-sm mt-2 flex items-center">
                                <span class="mr-1">⚠️</span>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Estado Field -->
                    <div>
                        <label class="block text-white font-semibold mb-2 flex items-center">
                            <span class="mr-2">📊</span>
                            Estado actual del hábito
                        </label>
                        <div class="relative">
                            <input type="number" name="estado" value="{{ old('estado') }}"
                                min="1" max="10" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-transparent backdrop-blur-sm"
                                placeholder="Del 1 al 10"
                                required>
                        </div>
                        <div class="flex justify-between text-xs text-white/60 mt-2">
                            <span>1 - Muy temprano</span>
                            <span>10 - Muy tarde</span>
                        </div>
                        @error('estado') 
                            <p class="text-red-300 text-sm mt-2 flex items-center">
                                <span class="mr-1">⚠️</span>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-motiveo-primary to-motiveo-secondary text-white py-4 px-6 rounded-xl font-bold text-lg hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center">
                        <span class="mr-2">✨</span>
                        Continuar al Cuestionario
                        <span class="ml-2">→</span>
                    </button>
                </form>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-white/50 text-sm">
                        💡 Estos datos nos ayudarán a personalizar tu experiencia
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>