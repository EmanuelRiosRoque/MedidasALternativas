<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class CatDocumentoCivil extends Model
{
    protected $table = 'cat_documentos_civil';
    protected $fillable = ['tipo', 'nombre'];

    public static function tipos(): array
    {
        return self::distinct()->pluck('tipo')->toArray();
    }

    public static function porTipo(string $tipo): array
    {
        return self::where('tipo', $tipo)
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();
    }
}
