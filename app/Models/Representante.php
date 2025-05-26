<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Representante extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitante_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
    ];
}
