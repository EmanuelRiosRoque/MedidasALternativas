<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// app/Models/Solicitud.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        "modalidad",
        "materia",
        "estatus_id",
        "derivado_canalizado",
        "numero_ticket",
        "institucion",
        "oficio",
        "cual_otro"
    ];

    public function personas()
    {
        return $this->hasMany(PersonaSolicitud::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }


}
