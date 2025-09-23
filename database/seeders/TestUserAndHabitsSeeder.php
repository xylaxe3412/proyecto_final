<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Habit;
use Illuminate\Database\Seeder;

class TestUserAndHabitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o encontrar usuario de prueba
        $user = User::firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Usuario de Prueba',
                'password' => bcrypt('password'),
                'xp' => 400, // Nivel 4 (400 XP)
                'level' => 5, // Ya está en nivel 5
            ]
        );

        // Crear hábitos de prueba con diferentes niveles de progreso
        $habits = [
            [
                'nombre' => 'Ejercicio Matutino',
                'name' => 'Ejercicio Matutino',
                'description' => 'Hacer ejercicio todas las mañanas',
                'categoria' => 'salud',
                'frequency' => 'diario',
                'motivation' => 'Quiero estar en forma',
                'duration_days' => 30,
                'current_day' => 15,
                'progreso_actual' => 15, // 15 completaciones
                'progreso_total' => 30,
                'is_active' => true,
                'start_date' => now()->subDays(15),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(15),
                'dias_racha' => 12, // Racha de 12 días
            ],
            [
                'nombre' => 'Leer 20 páginas',
                'name' => 'Leer 20 páginas',
                'description' => 'Leer al menos 20 páginas cada día',
                'categoria' => 'aprendizaje',
                'frequency' => 'diario',
                'motivation' => 'Quiero aprender más',
                'duration_days' => 21,
                'current_day' => 21,
                'progreso_actual' => 21, // 21 completaciones
                'progreso_total' => 21,
                'is_active' => false, // Ya completado
                'start_date' => now()->subDays(21),
                'next_due_date' => now()->subDay(),
                'expected_end_date' => now()->subDay(),
                'dias_racha' => 21, // Racha perfecta de 21 días
            ],
            [
                'nombre' => 'Meditar 10 minutos',
                'name' => 'Meditar 10 minutos',
                'description' => 'Practicar meditación diaria',
                'categoria' => 'bienestar',
                'frequency' => 'diario',
                'motivation' => 'Quiero paz mental',
                'duration_days' => 14,
                'current_day' => 8,
                'progreso_actual' => 8, // 8 completaciones
                'progreso_total' => 14,
                'is_active' => true,
                'start_date' => now()->subDays(8),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(6),
                'dias_racha' => 8, // Racha actual de 8 días
            ],
            [
                'nombre' => 'Caminar 30 minutos',
                'name' => 'Caminar 30 minutos',
                'description' => 'Caminar diariamente para mantenerse activo',
                'categoria' => 'salud',
                'frequency' => 'diario',
                'motivation' => 'Quiero mantenerme activo',
                'duration_days' => 45,
                'current_day' => 35,
                'progreso_actual' => 35, // 35 completaciones
                'progreso_total' => 45,
                'is_active' => true,
                'start_date' => now()->subDays(35),
                'next_due_date' => now(),
                'expected_end_date' => now()->addDays(10),
                'dias_racha' => 35, // ¡Racha impresionante de 35 días!
            ],
        ];

        foreach ($habits as $habitData) {
            Habit::create(array_merge($habitData, ['user_id' => $user->id]));
        }

        // Actualizar XP del usuario basado en los hábitos completados
        $totalCompletions = collect($habits)->sum('progreso_actual');
        $additionalXP = $totalCompletions * 20; // 20 XP por cada completación
        
        $user->update([
            'xp' => 400 + $additionalXP, // XP base + XP de hábitos
            'level' => $user->calculateLevel(400 + $additionalXP)
        ]);

        $this->command->info("Usuario creado con {$totalCompletions} hábitos completados");
        $this->command->info("XP total: " . (400 + $additionalXP));
        $this->command->info("Nivel: " . $user->calculateLevel(400 + $additionalXP));
    }
}
