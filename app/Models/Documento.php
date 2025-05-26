<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitante_id',
        'tipo',
        'nombre_original',
        'ruta',
        'extension',
        'size',
    ];
}
