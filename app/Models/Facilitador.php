<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facilitador extends Model
{
    protected $table = 'facilitadores'; // 👈 aquí forzamos el nombre correcto
    protected $fillable = ['nombre'];

}
