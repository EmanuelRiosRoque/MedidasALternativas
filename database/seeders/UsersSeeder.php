<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'n_empleado' => '0000000',
                'name' => 'Usuario Pruebas',
                'email' => 'pruebas@example.com',
                'password' => Hash::make('12345678'), // Cambia esto si lo deseas
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'n_empleado' => '8009933',
                'name' => 'Emanuel Rios Roque',
                'email' => 'emanuel.rios@example.com',
                'password' => Hash::make('12345678'), // Cambia esto si lo deseas
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
