<?php

namespace App\Console;

use App\Console\Commands\CheckDigimonEvolution;
use App\Console\Commands\UpdateDigimonAge;
use App\Console\Commands\UpdateUserDigimon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(UpdateUserDigimon::class)->everyMinute();
        $schedule->command(UpdateDigimonAge::class)->hourly();
        $schedule->command(CheckDigimonEvolution::class)->everyFiveMinutes();
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
