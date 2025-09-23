<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogos\CatPoderesJudiciales;

class CatPoderesJudicialesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['01','Aguascalientes','Poder Judicial del Estado de Aguascalientes'],
            ['02','Baja California','Poder Judicial del Estado de Baja California'],
            ['03','Baja California Sur','Poder Judicial del Estado de Baja California Sur'],
            ['04','Campeche','Poder Judicial del Estado de Campeche'],
            ['05','Coahuila de Zaragoza','Poder Judicial del Estado de Coahuila de Zaragoza'],
            ['06','Colima','Poder Judicial del Estado de Colima'],
            ['07','Chiapas','Poder Judicial del Estado de Chiapas'],
            ['08','Chihuahua','Poder Judicial del Estado de Chihuahua'],
            ['09','Ciudad de México','Poder Judicial de la Ciudad de México'],
            ['10','Durango','Poder Judicial del Estado de Durango'],
            ['11','Guanajuato','Poder Judicial del Estado de Guanajuato'],
            ['12','Guerrero','Poder Judicial del Estado de Guerrero'],
            ['13','Hidalgo','Poder Judicial del Estado de Hidalgo'],
            ['14','Jalisco','Poder Judicial del Estado de Jalisco'],
            ['15','México','Poder Judicial del Estado de México'],
            ['16','Michoacán de Ocampo','Poder Judicial del Estado de Michoacán de Ocampo'],
            ['17','Morelos','Poder Judicial del Estado de Morelos'],
            ['18','Nayarit','Poder Judicial del Estado de Nayarit'],
            ['19','Nuevo León','Poder Judicial del Estado de Nuevo León'],
            ['20','Oaxaca','Poder Judicial del Estado de Oaxaca'],
            ['21','Puebla','Poder Judicial del Estado de Puebla'],
            ['22','Querétaro','Poder Judicial del Estado de Querétaro'],
            ['23','Quintana Roo','Poder Judicial del Estado de Quintana Roo'],
            ['24','San Luis Potosí','Poder Judicial del Estado de San Luis Potosí'],
            ['25','Sinaloa','Poder Judicial del Estado de Sinaloa'],
            ['26','Sonora','Poder Judicial del Estado de Sonora'],
            ['27','Tabasco','Poder Judicial del Estado de Tabasco'],
            ['28','Tamaulipas','Poder Judicial del Estado de Tamaulipas'],
            ['29','Tlaxcala','Poder Judicial del Estado de Tlaxcala'],
            ['30','Veracruz de Ignacio de la Llave','Poder Judicial del Estado de Veracruz de Ignacio de la Llave'],
            ['31','Yucatán','Poder Judicial del Estado de Yucatán'],
            ['32','Zacatecas','Poder Judicial del Estado de Zacatecas'],
        ];

        foreach ($rows as [$cveEnt, $entidad, $pj]) {
            CatPoderesJudiciales::updateOrCreate(
                ['cve_ent' => $cveEnt],
                [
                    'entidad'        => $entidad,
                    'poder_judicial' => $pj,
                    'activo'         => true,
                ]
            );
        }
    }
}
