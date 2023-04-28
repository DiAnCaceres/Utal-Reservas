<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloques extends Model
{
    use HasFactory;
    protected $table = 'bloques';
    protected $fillable = ['id',"hora_inicio","hora_fin"];
}
