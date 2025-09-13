<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'solicitud_id',
        'facilitador_id',
        'estatus_id',
        'tipo_proceso_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'hora_inicio_invitado',
        'hora_fin_invitado',
        'descripcion',
        'materia',
        'color',
        'observacion',
        'url',
        'opcion_invitacion',
        'activo',
    ];

    // Relación con Solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    // Relación con Facilitador
    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }
}
