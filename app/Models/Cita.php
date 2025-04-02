<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $fillable = [
        'convenio_participante_id', 'fecha_hora', 'dia_semana',
        'url_sesion', 'se_presento', 'observaciones'
    ];

    public function participante(): BelongsTo
    {
        return $this->belongsTo(ConvenioParticipante::class, 'convenio_participante_id');
    }
}
