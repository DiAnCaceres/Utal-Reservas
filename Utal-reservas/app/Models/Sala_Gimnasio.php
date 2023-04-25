<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala_Gimnasio extends Model
{
    use HasFactory;
    protected $table = 'sala_gimnasios';
    protected $fillable = ["capacidad","reserva_id"];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class,"reserva_id");
    }
}
