<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogos\CatDocumentoFamiliar;

class CatDocumentosFamiliarSeeder extends Seeder
{
    public function run(): void
    {
        $documentos = [
            'Pensión alimenticia' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Oficio de descuento de pensión alimenticia',
                'Otro',
            ],
            'Guarda y custodia' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Resolución judicial previa',
                'Otro',
            ],
            'Visitas y convivencias' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Resolución judicial previa',
                'Otro',
            ],
            'Cuestiones patrimoniales derivadas de juicio sucesorio' => [
                'Resolución judicial',
                'Instrumento notarial',
                'Otro',
            ],
            'Derivados de la disolución de la sociedad conyugal' => [
                'Acta de matrimonio',
                'Resolución judicial',
                'Documentos a través de los cuales acrediten la propiedad de los bienes',
                'Otro',
            ],
            'Elaboración de convenio regulador de divorcio o separación' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Documentos a través de los cuales acrediten la propiedad de los bienes de la sociedad conyugal o para compensación de bienes',
                'Otro',
            ],
            'Modificación de los términos de resolución judicial' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Oficio de descuento de pensión alimenticia',
                'Otro',
            ],
            'Crisis de la convivencia' => [
                'Otro',
            ],
        ];

        foreach ($documentos as $tema => $docs) {
            foreach ($docs as $doc) {
                CatDocumentoFamiliar::create([
                    'tema' => $tema,
                    'nombre' => $doc,
                ]);
            }
        }
    }
}
