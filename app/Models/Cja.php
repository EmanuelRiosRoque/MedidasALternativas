<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Cja extends Model
{
    protected $fillable = [
        'solicitud_id',
        // Solicitante
        'propuesta_inicio_fecha',
        'propuesta_inicio_hora',
        'acepta_inicio',
        'fecha_vencimiento',
        // Invitado
        'fecha_propuesta',
        'acepta_inicio_inv',

        // Mediación 
        'mediacion_fecha_inicio',
        'mediacion_hora_inicio',
        'mediacion_fecha_termino',
        'mediacion_hora_termino',
        'fecha_envio_archivo',
        'conclucion_id',
        'persona_concluye_id',
    ];

    protected $casts = [
        'acepta_inicio'           => 'integer',
        'acepta_inicio_inv'       => 'integer',
    ];


    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
