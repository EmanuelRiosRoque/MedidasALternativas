<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatCancelacion extends Model
{
    use HasFactory;

    protected $table = 'cat_cancelacion';   // nombre de la tabla
    protected $fillable = ['motivo'];       // campos que se pueden asignar en masa
}
