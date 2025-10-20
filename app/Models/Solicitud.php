<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        "modalidad",
        "materia",
        "estatus_id",
        "tipo_proceso_id",
        "tipo_cancelacion_id",
        "co_mediador_id",
        "derivado_canalizado",
        "numero_ticket",
        "institucion",
        "oficio",
        "cual_otro",
        
        "facilitador_solicitante_id",
        "fecha_asignacion_solicitante",
        "hora_inicio_solicitante",
        "hora_fin_solicitante",

        "facilitador_invitado_id",
        "fecha_asignacion_invitado",
        "hora_inicio_invitado",
        "hora_fin_invitado",

        "folio_materia",
        "acudiran_juntos",
        "notas_observaciones"
    ];

    public function personas()
    {
        return $this->hasMany(PersonaSolicitud::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }

    public function facilitadorSolicitante()
    {
        return $this->belongsTo(Facilitador::class, 'facilitador_solicitante_id');
    }

    public function facilitadorInvitado()
    {
        return $this->belongsTo(Facilitador::class, 'facilitador_invitado_id');
    }
    public function coMediador()
    {
        return $this->belongsTo(Facilitador::class, 'co_mediador_id');
    }

    public function proceso()
    {
        return $this->belongsTo(CatTipoProceso::class, 'tipo_proceso_id');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function cancelacion()
    {
        return $this->belongsTo(CatCancelacion::class, 'tipo_cancelacion_id');
    }
}
