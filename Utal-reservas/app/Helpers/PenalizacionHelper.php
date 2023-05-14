<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\hacePenalizacion;

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
            $fecha_reserva=$reserva->fecha_reserva;
            $reserva_id=$reserva->reserva_id;
            $user_id=$reserva->user_id;
            $bloque_id=$reserva->bloque_id;

            //VERIFICAMOS QUE NO EXISTE ESA RESERVA COMO CANCELADA
            $noCancelaReserva = DB::table("historial_instancia_reservas")
                ->whereDate('fecha_reserva', $fecha_reserva)
                ->where('reserva_id', $reserva_id)
                ->where('user_id', $user_id)
                ->where('bloque_id', $bloque_id)
                ->where('estado_instancia_id', 5)           //marca como cancelada
                ->doesntExist();
            
            if ($noCancelaReserva){
                //VERIFICAMOS SI EXISTEN BLOQUES SEGUIDOS RESERVADOS
                $bloque_sgte=$bloque_id+1;
                $bloque_ant=$bloque_id-1;
                
                $bloqueSgte =  DB::table("historial_instancia_reservas")
                    ->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $reserva_id)
                    ->where('user_id', $user_id)
                    ->where('bloque_id', $bloque_sgte)   //si existe reserva de la persona en el siguiente bloque
                    ->where('estado_instancia_id', 1)
                    ->exists();

                $bloqueAnterior = DB::table("historial_instancia_reservas")
                    ->whereDate('fecha_reserva', $fecha_reserva)
                    ->where('reserva_id', $reserva_id)
                    ->where('user_id', $user_id)
                    ->where('bloque_id', $bloque_ant)   //si existe reserva de la persona en el anterior bloque
                    ->where('estado_instancia_id', 1)
                    ->exists();
                    
                if ($bloqueSgte){
                    //hay siguiente bloque no puedo seguir con la penalizacion ya que puede estar en progreso la reserva
                }
                else{
                    
                    if ($bloqueAnterior){   //ES LA SEGUNDA HORA DE UNA RESERVA AHORA PUEDO PENALIZAR LA HORA ANTERIOR EN CASO DE SER NECESARIO
                        VerificarHelper::hacePenalizacion($fecha_reserva,$reserva_id,$user_id,$bloque_ant,$fechaHoraActual);
                    }
                    //INDEPENDIENTE DE SI EXISTE UNA PENALIZACION ANTES DEBO MARCAR LA DE LA DEL BLOQUE ACTUAL
                    VerificarHelper::hacePenalizacion($fecha_reserva,$reserva_id,$user_id,$bloque_id,$fechaHoraActual);
                
                }
            }
            else{
                //marca como reserva cancelada, no debe recibir penalización
            }

            
        }
    }

}


