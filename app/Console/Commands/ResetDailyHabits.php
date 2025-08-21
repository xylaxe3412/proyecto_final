<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Habit;

class ResetDailyHabits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'habits:reset-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily habit status and update next due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting daily habit statuses...');
        
        // Obtener todos los hábitos activos
        $habits = Habit::where('is_active', true)->get();
        
        $resetCount = 0;
        
        foreach ($habits as $habit) {
            // Resetear completed_today si es un nuevo día
            if ($habit->next_due_date->isPast() && $habit->completed_today) {
                $habit->update(['completed_today' => false]);
                $resetCount++;
            }
            
            // Si se saltó un día sin completar, reiniciar racha
            if ($habit->next_due_date->isPast() && !$habit->completed_today) {
                $habit->update(['dias_racha' => 0]);
            }
        }
        
        $this->info("Reset completed for {$resetCount} habits.");
        
        return Command::SUCCESS;
    }
}
