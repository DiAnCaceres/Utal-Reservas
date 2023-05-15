<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerificarHelper{
    public static function hacePenalizacion($fecha_reserva,$reserva_id,$user_id,$bloque_id,$fechaHoraActual){
        //FILTRAR AQUELLAS RESERVAS QUE SI SE MARCARON COMO FINALIZADAS/ASISTE
        $noExisteFinalizada = DB::table("historial_instancia_reservas")
                ->whereDate('fecha_reserva', $fecha_reserva)
                ->where('reserva_id', $reserva_id)
                ->where('user_id', $user_id)
                ->where('bloque_id', $bloque_id)
                ->where('estado_instancia_id', 3)           //marca como finalizada
                //->orWhere('estado_instancia_id',5)          //o está marca como cancelada (este es un caso extremo si la primera reserva fue cancelada y la segunda no)
                ->doesntExist();
        
        if($noExisteFinalizada){
            //DEBO VERIFICAR SI EXISTE YA ESA RESERVA VENCIDA PARA NO AGREGARLA NUEVAMENTE
            $noExistePenalizacion = DB::table("penalizaciones")
            ->whereDate('fecha_reserva', $fecha_reserva)
            ->where('reserva_id', $reserva_id)
            ->where('user_id', $user_id)
            ->where('bloque_id', $bloque_id)
            ->doesntExist();
        
            if ($noExistePenalizacion){
                //AHORA CREO LA PENALIZACION Y CREAR EN EL REGISTRO QUE NO ASISTIÓ EN EL HISTORIAL
                DB::table('historial_instancia_reservas')->insert([
                    'user_id' => $user_id,
                    'fecha_reserva' => $fecha_reserva,
                    'reserva_id' => $reserva_id,
                    'bloque_id' => $bloque_id,
                    'fecha_estado' =>$fechaHoraActual->format('Y-m-d'),
                    'estado_instancia_id' => 4
                ]);
                DB::table('penalizaciones')->insert([
                    'user_id' => $user_id,
                    'fecha_reserva' => $fecha_reserva,
                    'reserva_id' => $reserva_id,
                    'bloque_id' => $bloque_id,
                    'fecha_penalizacion' => $fecha_reserva,
                    'estado_penalizacione_id' => 1
                ]);
            }
            else{
                //ya esta creada
            }
        }
        }
}




