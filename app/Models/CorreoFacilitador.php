<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CorreoFacilitador extends Model
{
    use HasFactory;

    protected $table = 'correos_facilitador';

    protected $fillable = [
        'facilitador_id',
        'email',
    ];

    public function facilitador()
    {
        return $this->belongsTo(Facilitador::class);
    }
}
