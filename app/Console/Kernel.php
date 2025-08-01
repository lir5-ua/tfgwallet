<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('recordatorios:enviar')->dailyAt('08:00');
        $schedule->command('optimize --optimize')->dailyAt('03:00');
    }


    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
