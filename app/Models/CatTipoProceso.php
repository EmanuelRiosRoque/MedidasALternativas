<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoProceso extends Model
{
    protected $table = 'cat_tipo_proceso';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
