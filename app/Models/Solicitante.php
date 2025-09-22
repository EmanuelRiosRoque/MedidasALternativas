<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitante extends Model
{
    use HasFactory;

    protected $table = 'solicitantes';

    protected $fillable = [
        'solicitud_id',
        'tipo_solicitante',
        'persona',
        'representante',
        'persona_invitado',
        'nombre',
        'apellido_p',
        'apellido_m',
        'sexo',
        'edad',
        'fecha_nacimiento',
        'escolaridad',
        'ocupacion',
        'nacionalidad',
        'tipo_domicilio',
        'calle',
        'colonia',
        'municipio',
        'entidad_federativa',
        'cp',
        'correo',
        'identificacion',
        'acta_notarial',
        'acta_de_nacimiento',
        'resolucion_judicial',
        'formato_privacidad',
        'como_se_entero',
        'razon_social',
        'rfc',
        'instrumento',
        'fecha_instrumento',
        'domicilio',
        'estado_civil',
        'facilitador_id',
        'estatus_id',
    ];

    protected $casts = [
        'identificacion' => 'array',
        'formato_privacidad' => 'array',
    ];

    // Relaciones

    public function correos()
    {
        return $this->hasMany(Correo::class);
    }

    public function telefonos()
    {
        return $this->hasMany(Telefono::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }

   public function solicitud()
{
    return $this->belongsTo(Solicitud::class, 'solicitud_id');
}

}
