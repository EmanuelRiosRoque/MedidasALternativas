<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatEscolaridadSeeder extends Seeder
{
    public function run(): void
    {
        $escolaridades = [
            'Primaria inconclusa',
            'Primaria terminada',
            'Secundaria inconclusa',
            'Secundaria terminada',
            'Media superior inconclusa',
            'Media superior terminada',
            'Carrera técnica inconclusa',
            'Carrera técnica terminada',
            'Carrera comercial inconclusa',
            'Carrera comercial terminada',
            'Licenciatura inconclusa',
            'Licenciatura terminada',
            'Maestría inconclusa',
            'Maestría terminada',
            'Doctorado inconcluso',
            'Doctorado terminado',
            'Otra',
        ];

        foreach ($escolaridades as $nombre) {
            DB::table('cat_escolaridad')->insert([
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
