<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secuencia extends Model
{
    protected $fillable = ['nombre', 'valor_actual'];

    public $timestamps = true;
}
