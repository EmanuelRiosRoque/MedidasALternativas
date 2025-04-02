<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConvenioParticipante extends Model
{
    protected $fillable = ['convenio_id', 'persona_id', 'tipo_rol', 'es_representante'];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function convenio(): BelongsTo
    {
        return $this->belongsTo(Convenio::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }
}
