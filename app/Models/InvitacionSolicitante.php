<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitacionSolicitante extends Model
{
    use HasFactory;

    protected $table = 'invitaciones_solicitante';

    protected $fillable = [
        // Relaciones principales
        'solicitud_id',
        'facilitador_id',
        'tipo_proceso_id',
        'estatus_id',
        'solicitante_id',

        // Control
        'numero_inv',

        // Solicitante
        'fecha_envio',
        'medio_envio',
        'acepta_mediacion',
    ];

    protected $casts = [
        'acepta_mediacion'         => 'integer',
        'atendio_sesion'           => 'integer',
        'acepta_mediacion_inv'     => 'integer',
        'acepta_inicio'            => 'integer',
        'acepta_inicio_inv'        => 'integer',
    ];

    /* ================================
     |  RELACIONES
     ================================ */

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }

    public function tipoProceso()
    {
        return $this->belongsTo(CatTipoProceso::class, 'tipo_proceso_id');
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    // public function observaciones()
    // {
    //     return $this->hasMany(InvitacionObservacion::class, 'invitacion_id');
    // }
}
