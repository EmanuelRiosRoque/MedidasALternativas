<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatMotivosCierre extends Model
{
    use HasFactory;

    protected $table = 'cat_motivos_cierres';

    protected $fillable = ['nombre'];

    // public $timestamps = false;

}
