<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea los roles si no existen
        $civilRole = Role::firstOrCreate(['name' => 'civil']);
        $familiarRole = Role::firstOrCreate(['name' => 'familiar']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Usuario 1 con rol 'civil'
        $user1 = User::create([
            'n_empleado' => '0000000',
            'name' => 'Usuario civil',
            'email' => 'pruebas@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $user1->assignRole($civilRole);

        $user1 = User::create([
            'n_empleado' => '0000001',
            'name' => 'Usuario familiar',
            'email' => 'pruebas@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $user1->assignRole($familiarRole);

        // Usuario 2 con rol 'familiar'
        $user2 = User::create([
            'n_empleado' => '8009933',
            'name' => 'Emanuel Rios Roque',
            'email' => 'emanuel.rios@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $user2->assignRole($adminRole);
    }
}
