<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SepomexColonia extends Model
{
    protected $table = 'sepomex_colonias';

    protected $fillable = [
        'codigo_postal',
        'colonia',
        'tipo_asentamiento',
        'municipio',
        'estado',
        'zona',
    ];

    public $timestamps = false; // Ya que esta tabla es de catálogo, no necesita created_at/updated_at
}
