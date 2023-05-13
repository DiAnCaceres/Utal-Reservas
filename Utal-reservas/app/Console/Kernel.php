<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Helpers\PenalizacionHelper;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //CADA UNA HORA SE VERIFICARÁ EL ESTADO DE LAS RESERVAS Q PUEDEN ESTAR VENCIDAS
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')
                ->cron('* * * * *')
                ->between('8:30', '20:30');
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
