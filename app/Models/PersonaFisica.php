<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaFisica extends Model
{
    protected $table = 'personas_fisicas';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
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
        'rfc',
    ];

    public function solicitudes()
    {
        return $this->hasMany(PersonaSolicitud::class)
            ->where('tipo_persona', 'fisica');
    }
}
