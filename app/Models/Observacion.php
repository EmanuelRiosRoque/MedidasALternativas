<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{

    protected $table = 'observaciones';


    protected $fillable = [
        'solicitud_id',
        'fecha_observacion',
        'observacion',
    ];


    public function solicitud()
    {
        return $this->belongsTo(\App\Models\Solicitud::class);
    }
}
