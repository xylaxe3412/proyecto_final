<?php

namespace App\Console\Commands;

use App\Services\AchievementService;
use Illuminate\Console\Command;

class InitializeAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievements:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa los logros predeterminados del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(AchievementService::class);
        $service->createDefaultAchievements();
        
        $this->info('¡Los logros han sido inicializados correctamente!');
        $this->info('Se han creado logros para:');
        $this->info('- Niveles alcanzados');
        $this->info('- Hábitos completados');
        $this->info('- XP total acumulada');
    }
}
