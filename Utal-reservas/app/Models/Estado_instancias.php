<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado_instancias extends Model
{
    use HasFactory;
    protected $table = 'Estado_instancias';
    protected $fillable = ['id',"nombre_estado"];
}
