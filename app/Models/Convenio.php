<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Convenio extends Model
{
    protected $fillable = [
        'numero_consecutivo', 'lugar_celebracion', 'fecha_celebracion',
        'numero_registro', 'modalidad', 'tipo_mecanismo', 'materia',
        'conflicto', 'tipo_cumplimiento', 'tipo_solucion',
        'rango_monetario', 'otro_tipo_acuerdo', 'hipervinculo_convenio',
        'fecha_registro', 'facilitador_id'
    ];

    public function facilitador(): BelongsTo
    {
        return $this->belongsTo(Facilitador::class);
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(ConvenioParticipante::class);
    }

    public function documentosGenerales(): HasMany
    {
        return $this->hasMany(DocumentosGenerales::class);
    }
}
