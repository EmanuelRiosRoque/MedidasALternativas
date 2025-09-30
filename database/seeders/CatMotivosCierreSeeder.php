<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogos\CatMotivosCierre;

class CatMotivosCierreSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = [
            'Asunto informativo',
            'Asunto no mediable',
            'A petición del solicitante',
            'A petición del invitado',
            'No aceptó el solicitante',
            'No aceptó el invitado',
            'No vuelve a comunicarse el solicitante',
            'No vuelve a comunicarse el invitado',
            'No competentes',
            'Otro',
        ];

        foreach ($nombres as $nombre) {
            CatMotivosCierre::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
