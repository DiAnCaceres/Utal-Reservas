<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;
    protected $table = 'canchas';
    protected $fillable = ["reserva_id"];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class,"reserva_id");
    }
}
