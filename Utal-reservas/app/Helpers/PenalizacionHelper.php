<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenalizacionHelper
{
    public static function verificarnoAsiste()
    {
        $fechaHoraActual = now();
        
        //OBTENER REGISTROS DE HOY QUE PASEN LA HORA ACTUAL
        $instanciasReservas = DB::table("instancia_reservas")
                    ->join("bloques", "bloques.id", "=", "instancia_reservas.bloque_id")
                    ->where('instancia_reservas.fecha_reserva',"=",$fechaHoraActual->format('Y-m-d'))     //coincide con el dia actual
                    ->where('bloques.hora_fin', '<', $fechaHoraActual->format('H:i:s'))     //su bloque terminó 
                    ->get();

        //AHORA VEO CADA RESERVA
        foreach ($instanciasReservas as $reserva) {

            //FILTRAR AQUELLAS RESERVAS QUE SI SE MARCARON COMO FINALIZADAS/ASISTE
            $noExisteFinalizada = DB::table("historial_instancia_reservas")
                    ->whereDate('fecha_reserva', $reserva->fecha_reserva)
                    ->where('reserva_id', $reserva->reserva_id)
                    ->where('user_id', $reserva->user_id)
                    ->where('bloque_id', $reserva->bloque_id)
                    ->where('estado_instancia_id', 3)
                    ->doesntExist();
            
            if($noExisteFinalizada){
                //DEBO VERIFICAR SI EXISTE YA ESA RESERVA VENCIDA PARA NO AGREGARLA NUEVAMENTE
                $noExistePenalizacion = DB::table("penalizaciones")
                ->whereDate('fecha_reserva', $reserva->fecha_reserva)
                ->where('reserva_id', $reserva->reserva_id)
                ->where('user_id', $reserva->user_id)
                ->where('bloque_id', $reserva->bloque_id)
                ->doesntExist();
        
                if ($noExistePenalizacion){
                    //AHORA CREO LA PENALIZACION Y CREAR EN EL REGISTRO QUE NO ASISTIÓ EN EL HISTORIAL
                    DB::table('historial_instancia_reservas')->insert([
                        'user_id' => $reserva->user_id,
                        'fecha_reserva' => $reserva->fecha_reserva,
                        'reserva_id' => $reserva->reserva_id,
                        'bloque_id' => $reserva->bloque_id,
                        'fecha_estado' =>$fechaHoraActual->format('Y-m-d'),
                        'estado_instancia_id' => 4
                    ]);
                    DB::table('penalizaciones')->insert([
                        'user_id' => $reserva->user_id,
                        'fecha_reserva' => $reserva->fecha_reserva,
                        'reserva_id' => $reserva->reserva_id,
                        'bloque_id' => $reserva->bloque_id,
                        'fecha_penalizacion' => $reserva->fecha_reserva,
                        'estado_penalizacione_id' => 1
                    ]);
                }
                else{
                    //ya esta creada
                }
            }
        }
    }
}
