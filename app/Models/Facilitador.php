<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facilitador extends Model
{
    protected $fillable = ['nombre', 'numero_certificacion', 'tipo'];

    public function convenios(): HasMany
    {
        return $this->hasMany(Convenio::class);
    }
}
