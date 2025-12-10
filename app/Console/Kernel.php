<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Esempio standard Laravel:
        // $schedule->command('inspire')->hourly();

        // ⭐ CRON ICS AUTOMATICO DUKARES
        // Ogni 10 minuti importa gli ICS di tutte le strutture che hanno ics_url
        $schedule->command('dukares:import-ics')->everyTenMinutes();
        // Se vuoi ogni 5 minuti: ->everyFiveMinutes();
        // Se vuoi ogni ora:      ->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
