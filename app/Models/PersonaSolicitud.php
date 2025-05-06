<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonaSolicitud extends Model
{
    protected $table = 'persona_solicitud';
    protected $fillable = ['solicitud_id', 'persona_id', 'tipo_persona', 'rol'];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function personaFisica()
    {
        return $this->belongsTo(PersonaFisica::class, 'persona_id');
    }
    
    public function personaMoral()
    {
        return $this->belongsTo(PersonaMoral::class, 'persona_id');
    }
    
    public function personaFamiliar()
    {
        return $this->belongsTo(PersonaFamiliar::class, 'persona_id');
    }

    public function persona()
{
    return match ($this->tipo_persona) {
        'fisica' => $this->personaFisica(),
        'moral' => $this->personaMoral(),
        'familiar' => $this->personaFamiliar(),
        default => null,
    };
}

}
