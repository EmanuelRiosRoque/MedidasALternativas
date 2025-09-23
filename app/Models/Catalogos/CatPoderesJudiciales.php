<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class CatPoderesJudiciales extends Model
{
    protected $table = 'cat_poderes_judiciales';

    protected $fillable = [
        'cve_ent',
        'entidad',
        'poder_judicial',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
