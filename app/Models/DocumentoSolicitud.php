<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoSolicitud extends Model
{
    use HasFactory;

        protected $table = 'documento_solicituds'; // 👈 aquí se corrige


     protected $fillable = [
        'solicitud_id',
        'tipo',
        'nombre_original',
        'ruta',
        'extension',
        'size',
    ];
}
