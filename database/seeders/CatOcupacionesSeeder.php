<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatOcupacionesSeeder extends Seeder
{
    public function run(): void
    {
        $ocupaciones = [
            'Comerciante',
            'Desempleado',
            'Empleado',
            'Estudiante',
            'Hogar',
            'Jubilado',
            'Oficio independiente',
            'Profesionista independiente',
            'Servidor Público',
            'Otra',
        ];

        foreach ($ocupaciones as $nombre) {
            DB::table('cat_ocupaciones')->insert([
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
