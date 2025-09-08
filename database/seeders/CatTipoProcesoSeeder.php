<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatTipoProceso;

class CatTipoProcesoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'Pre-Mediación','descripcion' => 'Proceso previo a mediación'],
            ['nombre' => 'Mediación',    'descripcion' => 'Proceso en etapa de mediación'],
            ['nombre' => 'Remediación',  'descripcion' => 'Proceso en etapa de remediación'],
        ];

        foreach ($data as $item) {
            CatTipoProceso::firstOrCreate(
                ['nombre' => $item['nombre']], // evita duplicados si ya existe
                ['descripcion' => $item['descripcion']]
            );
        }
    }
}
