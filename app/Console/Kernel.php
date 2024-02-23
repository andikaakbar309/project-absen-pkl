<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Menjadwalkan command untuk menjalankan setiap jam 9 pagi dengan zona waktu Jakarta
        $schedule->command('attendance:input')
                 ->timezone('Asia/Jakarta') // Tentukan zona waktu di sini
                 ->dailyAt('09:15');
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
