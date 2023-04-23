<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $table = "reservas";
    protected $fillable = ["nombre","ubicacion","estado"];
    
    public function sala_estudio(){
        return $this->hasOne(Sala_Estudio::class,"reserva_id");
    }
    public function sala_gimnasio(){
        return $this->hasOne(Sala_Gimnasio::class,"reserva_id");
    } 
    public function implemento(){
        return $this->hasOne(Implemento::class,"reserva_id");
    }
    public function canchas(){
        return $this->hasOne(Cancha::class,"reserva_id");
    } 
}
