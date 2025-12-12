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

        // ⭐ TEST ICS: esegui ogni minuto
        $schedule->command('dukares:import-ics')->everyMinute();
        // Quando funziona, cambialo in ->everyTenMinutes();
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
