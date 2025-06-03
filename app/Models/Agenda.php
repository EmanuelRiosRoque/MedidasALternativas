<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'solicitud_id',
        'facilitador_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'descripcion',
        'materia',
        'color',
        'motivo_reasignacion'
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
}
