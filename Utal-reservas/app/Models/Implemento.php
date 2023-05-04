<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Implemento extends Model
{
    use HasFactory;
    protected $table = 'implementos';
    protected $fillable = ["cantidad","reserva_id"];
    public $timestamps = false;
    public function reserva()
    {
        return $this->belongsTo(Reserva::class,"reserva_id");
    }
}
