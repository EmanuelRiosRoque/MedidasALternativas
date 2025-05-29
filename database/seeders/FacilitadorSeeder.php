<?php

namespace Database\Seeders;

use App\Models\Facilitador;
use Illuminate\Database\Seeder;

class FacilitadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nombres = [
            'Ana López',
            'Carlos Martínez',
            'Beatriz Hernández',
            'José Luis Ramírez',
            'Mariela Torres',
            'Fernando Sánchez',
            'Sofía González',
            'Ricardo Pérez',
            'Laura Mendoza',
            'Diego Herrera',
        ];

        foreach ($nombres as $nombre) {
            Facilitador::create(['nombre' => $nombre]);
        }
    }
}
