<?php 

use Illuminate\Support\Facades\DB;
use App\Models\Secuencia;

function siguienteValorSecuencia(string $materia): int
{
    $anio = now()->year;
    $nombre = strtolower($materia) . '_' . $anio;
    $valor = null;

    DB::transaction(function () use (&$valor, $nombre) {
        $secuencia = Secuencia::where('nombre', $nombre)
            ->lockForUpdate()
            ->first();

        if (!$secuencia) {
            $secuencia = Secuencia::create([
                'nombre' => $nombre,
                'valor_actual' => 1,
            ]);
            $valor = 1;
        } else {
            $secuencia->increment('valor_actual');
            $valor = $secuencia->valor_actual;
        }
    });

    return $valor;
}
