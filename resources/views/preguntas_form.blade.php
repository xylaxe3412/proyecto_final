<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario - Motiveo</title>
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
                            <span class="text-white text-sm font-bold">✓</span>
                        </div>
                        <div class="w-8 bg-motiveo-success h-0.5 rounded-full"></div>
                        <div class="w-8 h-8 bg-motiveo-warning rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Card Container -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-motiveo-accent to-motiveo-success rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl">📝</span>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-2">Cuestionario de Rutina</h2>
                    <p class="text-white/70 font-medium">Personaliza tu experiencia respondiendo estas preguntas</p>
                    
                    <!-- User's habit preview -->
                    <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                        <div class="flex items-center justify-center space-x-2 text-white/80">
                            <span class="text-sm">🎯 Configurando:</span>
                            <span class="font-semibold text-motiveo-warning">{{ $form['habito'] ?? 'Tu hábito' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('preguntas_form.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="nombre" value="{{ $form['nombre'] ?? '' }}">
                    <input type="hidden" name="habito" value="{{ $form['habito'] ?? '' }}">
                    <input type="hidden" name="estado" value="{{ $form['estado'] ?? '' }}">

                    @php
                        $preguntas = [
                            ['icon' => '⏰', 'text' => '¿En cuánto tiempo quieres logralo?', 'placeholder' => 'Ej: 30 días, 3 meses...'],
                            ['icon' => '💪', 'text' => '¿Con qué intensidad dedicarás tiempo diario?', 'placeholder' => 'Ej: Baja, moderada, alta...'],
                            ['icon' => '🌅', 'text' => '¿Prefieres mañanas o noches para tu hábito?', 'placeholder' => 'Ej: Mañanas, tardes, noches...'],
                            ['icon' => '⏱️', 'text' => '¿Cuántos minutos diarios destinarás?', 'placeholder' => 'Ej: 15 minutos, 30 minutos...'],
                            ['icon' => '🤝', 'text' => '¿Necesitas apoyo de alguien?', 'placeholder' => 'Ej: Familia, amigos, coach...'],
                            ['icon' => '🔔', 'text' => '¿Qué recordatorios te ayudarían?', 'placeholder' => 'Ej: Alarmas, notas, aplicaciones...'],
                            ['icon' => '📈', 'text' => '¿Cómo medirás tu progreso?', 'placeholder' => 'Ej: Calendario, app, diario...'],
                            ['icon' => '🚧', 'text' => '¿Qué obstáculos prevés?', 'placeholder' => 'Ej: Falta de tiempo, motivación...'],
                            ['icon' => '🎉', 'text' => '¿Cómo celebrarás tus avances?', 'placeholder' => 'Ej: Premios, actividades especiales...'],
                            ['icon' => '🎯', 'text' => '¿Cuál es tu motivo principal?', 'placeholder' => 'Ej: Salud, bienestar, crecimiento...']
                        ];
                    @endphp

                    @foreach ($preguntas as $idx => $pregunta)
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-white/10 transition-all duration-300">
                            <label class="block text-white font-semibold mb-3 flex items-center">
                                <span class="text-2xl mr-3">{{ $pregunta['icon'] }}</span>
                                <span class="text-lg">{{ $idx+1 }}. {{ $pregunta['text'] }}</span>
                            </label>
                            <textarea 
                                name="respuesta_{{ $idx+1 }}"
                                rows="2"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-motiveo-primary focus:border-transparent backdrop-blur-sm resize-none"
                                placeholder="{{ $pregunta['placeholder'] }}"
                                required></textarea>
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-motiveo-success to-motiveo-accent text-white py-4 px-6 rounded-xl font-bold text-lg hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center">
                            <span class="mr-2">🚀</span>
                            Finalizar y Crear mi Hábito
                            <span class="ml-2">✨</span>
                        </button>
                    </div>
                </form>

                <!-- Progress Info -->
                <div class="mt-6 text-center">
                    <p class="text-white/50 text-sm">
                        🔐 Tus respuestas nos ayudan a crear un plan personalizado
                    </p>
                    <div class="flex items-center justify-center mt-3 space-x-2">
                        <div class="w-2 h-2 bg-motiveo-success rounded-full"></div>
                        <div class="w-2 h-2 bg-motiveo-success rounded-full"></div>
                        <div class="w-2 h-2 bg-motiveo-success rounded-full"></div>
                        <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>