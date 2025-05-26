<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Correo extends Model
{
    use HasFactory;

    protected $fillable = ['solicitante_id','email'];
}
