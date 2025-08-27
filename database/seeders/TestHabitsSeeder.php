<?php

namespace Database\Seeders;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestHabitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'xp' => 100,
                'level' => 2,
            ]);
        }

        // Create test habits with template IDs (using older versions)
        $testHabits = [
            [
                'user_id' => $user->id,
                'nombre' => 'Ejercicio Matutino',
                'name' => 'Ejercicio Matutino',
                'description' => 'Hacer ejercicio todas las mañanas para mantenerme en forma',
                'categoria' => 'salud',
                'frequency' => 'diario',
                'motivation' => 'Quiero estar saludable y lleno de energía',
                'duration_days' => 30,
                'current_day' => 5,
                'start_date' => now()->subDays(4),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(25),
                'progreso_total' => 30,
                'is_active' => true,
                'template_id' => 'ejercicio_diario',
                'template_version' => '1.0', // Versión anterior para mostrar actualizaciones
                'sync_enabled' => true,
            ],
            [
                'user_id' => $user->id,
                'nombre' => 'Meditación Diaria',
                'name' => 'Meditación Diaria',
                'description' => 'Meditar 10 minutos cada día para reducir el estrés',
                'categoria' => 'bienestar',
                'frequency' => 'diario',
                'motivation' => 'Quiero tener más paz mental y claridad',
                'duration_days' => 21,
                'current_day' => 3,
                'start_date' => now()->subDays(2),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(18),
                'progreso_total' => 21,
                'is_active' => true,
                'template_id' => 'meditacion_mindfulness',
                'template_version' => '1.0', // Versión anterior para mostrar actualizaciones
                'sync_enabled' => true,
            ],
            [
                'user_id' => $user->id,
                'nombre' => 'Lectura Nocturna',
                'name' => 'Lectura Nocturna',
                'description' => 'Leer al menos 20 páginas antes de dormir',
                'categoria' => 'aprendizaje',
                'frequency' => 'diario',
                'motivation' => 'Quiero expandir mi conocimiento y relajarme',
                'duration_days' => 30,
                'current_day' => 1,
                'start_date' => now(),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(29),
                'progreso_total' => 30,
                'is_active' => true,
                'template_id' => 'lectura_diaria',
                'template_version' => '1.0',
                'sync_enabled' => true,
            ],
            [
                'user_id' => $user->id,
                'nombre' => 'Organización del Espacio',
                'name' => 'Organización del Espacio',
                'description' => 'Organizar mi espacio de trabajo diariamente',
                'categoria' => 'productividad',
                'frequency' => 'diario',
                'motivation' => 'Quiero ser más productivo y eficiente',
                'duration_days' => 30,
                'current_day' => 7,
                'start_date' => now()->subDays(6),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(23),
                'progreso_total' => 30,
                'is_active' => true,
                'template_id' => 'organizacion_productividad',
                'template_version' => '1.0',
                'sync_enabled' => true,
            ]
        ];

        foreach ($testHabits as $habitData) {
            Habit::create($habitData);
        }

        $this->command->info('Test habits created successfully!');
    }
}
