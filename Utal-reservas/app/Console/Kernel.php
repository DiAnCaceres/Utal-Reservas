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
        //****NO TOCAR****
        //CADA UNA HORA SE VERIFICARÁ EL ESTADO DE LAS RESERVAS Q PUEDEN ESTAR VENCIDAS
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('9:40');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('10:50');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('12:00');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('13:10');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('14:20');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('15:30');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('16:40');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('17:50');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('19:00');
        $schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('20:10');
        //****NO TOCAR****

        //PARA TESTEAR SE PUEDE CREAR MANUALMENTE INSTANCIAS DE RESERVAS Y ANOTARLAS EN EL HISTORIAL TAMBIEN (DEBE TENER COHERENCIA)

        //AQUI SE PUEDEN HACER PRUEBAS PARA HACER LLAMADO A LA FUNCION EN UNA HORA ESPECÍFICA
        //$schedule->call('App\Helpers\PenalizacionHelper::verificarnoAsiste')->dailyAt('18:01');
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
