<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaFamiliar extends Model
{
    protected $table = 'persona_familiars'; // Laravel pluraliza así por convención

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'edad',
        'escolaridad',
        'ocupacion',
        'tipo_domicilio',
        'calle',
        'colonia',
        'municipio',
        'entidad_federativa',
        'cp',
        'estado_civil',
    ];

    public function solicitudes()
    {
        return $this->hasMany(PersonaSolicitud::class)
            ->where('tipo_persona', 'familiar');
    }
}
