<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    use HasFactory;

    protected $table = 'invitaciones';

    protected $fillable = [
        'modalidad',
        'solicitud_id',
        'facilitador_id',
        'tipo_proceso_id',
        'url',
        'fecha_envio',
        'fecha_atencion',
        'hora_inicio',
        'hora_fin',
        'hora_inicio_invitado',
        'hora_fin_invitado',
        'numero_inv',
        'asistio',
        'acepta_proceso',
        'estatus_id',
        'acudiran_juntos',
    ];
    

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function facilitador()
    {
        return $this->belongsTo(User::class, 'facilitador_id');
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }
}
