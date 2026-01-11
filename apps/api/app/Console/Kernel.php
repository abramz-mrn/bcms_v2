<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('bcms:invoice-generate')->dailyAt('00:30');
        $schedule->command('bcms:reminder-dispatch')->hourly();
        $schedule->command('bcms:auto-enforce')->hourly();
        $schedule->command('bcms:provisioning-sync')->everyFifteenMinutes();
    }
}
