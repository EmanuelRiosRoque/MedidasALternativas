<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class CatDocumentoFamiliar extends Model
{
    protected $table = 'cat_documentos_familiar';
    protected $fillable = ['tema', 'nombre'];

    public static function tipos(): array
    {
        return self::distinct()->pluck('tema')->toArray();
    }

    public static function porTipo(string $tema): array
    {
        return self::where('tema', $tema)
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();
    }
}
