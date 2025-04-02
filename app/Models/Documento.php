<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'convenio_participante_id', 'tipo_documento', 'nombre_archivo',
        'ruta_archivo', 'descripcion', 'fecha_subida'
    ];

    public function participante(): BelongsTo
    {
        return $this->belongsTo(ConvenioParticipante::class, 'convenio_participante_id');
    }
}
