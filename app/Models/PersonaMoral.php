<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaMoral extends Model
{
    protected $table = 'personas_morales';

    protected $fillable = [
        'razon_social',
        'rfc',
        'instrumento',
        'fecha_instrumento',
        'tipo_domicilio',
        'calle',
        'colonia',
        'municipio',
        'entidad_federativa',
        'cp',
    ];

    public function solicitudes()
    {
        return $this->hasMany(PersonaSolicitud::class)
            ->where('tipo_persona', 'moral');
    }
}
