<?php
namespace App\Models;

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

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
