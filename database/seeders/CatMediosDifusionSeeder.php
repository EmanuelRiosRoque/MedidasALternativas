<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatMediosDifusionSeeder extends Seeder
{
    public function run(): void
    {
        $medios = [
            'Alcaldía',
            'Cartel',
            'Comisión de Derechos Humanos de la CDMX',
            'Comisión Nacional de Derechos Humanos',
            'Fiscalía de la CDMX',
            'Folleto',
            'Juzgado',
            'Centro de Atención a las Mujeres',
            'Locatel',
            'Consejo Ciudadano',
            'Internet',
            'Invitación CJA',
            'Por otra persona',
            'Periódico',
            'Radio',
            'Televisión',
            'UGJ-Civil Postulatoria',
            'UGJ-Civil Preliminar',
            'Juzgado Cívico',
        ];

        foreach ($medios as $nombre) {
            DB::table('cat_medios_difusion')->insert([
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
