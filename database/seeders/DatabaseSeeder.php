<?php

namespace Database\Seeders;

use App\Models\Catalogos\CatDocumentoFamiliar;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            EstatusSeeder::class,
            FacilitadorSeeder::class,
            CatEscolaridadSeeder::class,
            CatMediosDifusionSeeder::class,
            CatOcupacionesSeeder::class,
            CatDocumentosCivilSeeder::class,
            CatDocumentosFamiliarSeeder::class,
        ]);
    }
}
