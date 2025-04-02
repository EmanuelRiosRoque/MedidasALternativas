<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    protected $fillable = [
        'tipo_persona', 'nombre', 'sexo', 'edad', 'fecha_nacimiento',
        'escolaridad', 'ocupacion', 'nacionalidad', 'estado_civil',
        'telefono', 'tipo_domicilio', 'calle', 'colonia', 'municipio',
        'entidad', 'rfc', 'instrumento_notarial'
    ];

    public function participaciones(): HasMany
    {
        return $this->hasMany(ConvenioParticipante::class);
    }
}
