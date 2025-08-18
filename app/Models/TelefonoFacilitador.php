<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelefonoFacilitador extends Model
{
    use HasFactory;

    protected $table = 'telefonos_facilitador';

    protected $fillable = [
        'facilitador_id',
        'numero',
        'tipo',
    ];

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }
}
