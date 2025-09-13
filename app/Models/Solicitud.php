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
        "tipo_proceso_id",
        "tipo_cancelacion_id",
        "co_mediador_id",
        "derivado_canalizado",
        "numero_ticket",
        "institucion",
        "oficio",
        "cual_otro",
        "facilitador_id",
        "folio_materia",
        "acudiran_juntos"
    ];

    public function personas()
    {
        return $this->hasMany(PersonaSolicitud::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }

    public function coMediador()
    {
        return $this->belongsTo(Facilitador::class, 'co_mediador_id');
    }

    public function proceso()
    {
        return $this->belongsTo(CatTipoProceso::class, 'tipo_proceso_id');
    }
}
