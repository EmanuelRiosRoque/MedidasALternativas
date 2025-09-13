<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatCancelacion;

class CatCancelacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motivos = [
            'No le interesa la mediación',
            'Tenía otro compromiso',
            'No confía en el proceso',
            'Otro',
        ];

        foreach ($motivos as $motivo) {
            CatCancelacion::create([
                'motivo' => $motivo,
            ]);
        }
    }
}
