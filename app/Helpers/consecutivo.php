<?php

use Illuminate\Support\Facades\DB;
use App\Models\Secuencia;

if (!function_exists('siguienteValorSecuencia')) {
    function siguienteValorSecuencia(string $materia): int
    {
        $anio = now()->year;
        $nombre = strtolower(trim($materia)) . '_' . $anio;
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
                $secuencia->refresh(); // Asegura que $secuencia tenga el nuevo valor
                $valor = $secuencia->valor_actual;
            }
        });

        return (int) $valor;
    }
}
