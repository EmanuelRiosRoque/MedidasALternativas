<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatDocumentosCivilSeeder extends Seeder
{
    public function run(): void
    {
        $documentos = [
            "Arrendamiento" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Escritura del inmueble para acreditar disponibilidad",
                "Recibo de rentas, en su caso",
                "Recibo de pago de servicios, en su caso",
                "Poder notarial en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Otro"
            ],
            "Comisión mercantil" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas o recibos",
                "Acta constitutiva",
                "Otro"
            ],
            "Comodato" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Contrato de comodato",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Otro"
            ],
            "Compraventa" => [
                "Identificación oficial vigente con fotografía",
                "Escritura del inmueble, para acreditar disponibilidad",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal",
                "Otro"
            ],
            "Condominio" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Nombramiento de Administrador extendido por PROSOC",
                "Recibo de cuotas condominales, en su caso",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Otro"
            ],
            "Copropiedad" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble",
                "Otro"
            ],
            "Derecho de habitación" => [
                "Identificación oficial vigente con fotografía",
                "Escritura del inmueble, para acreditar disponibilidad",
                "Contrato",
                "Recibo de pago de rentas, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Otro"
            ],
            "Derecho de autor" => [
                "Identificación oficial vigente con fotografía",
                "El registro de la obra ante el INDAUTOR",
                "Otro"
            ],
            "Donación" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Certificado de libertad de gravamen con fecha de expedición no mayor a seis meses",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Otro"
            ],
            "Fideicomiso" => [
                "Identificación oficial vigente con fotografía",
                "Contrato con cláusulas relacionadas con el fideicomiso",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Franquicia" => [
                "Identificación oficial vigente con fotografía",
                "Contrato de adquisición de franquicia",
                "Facturas o recibos",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Hipoteca" => [
                "Identificación oficial vigente con fotografía",
                "El contrato de hipoteca",
                "Pagos relacionados con la hipoteca",
                "Certificado de libertad de Gravamen, con fecha de expedición no mayor a seis meses",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Otro"
            ],
            "Hospedaje" => [
                "Identificación oficial vigente con fotografía",
                "Reservación",
                "Contrato, en su caso",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Mutuo con interés" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Pagaré, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Mutuo simple" => [
                "Identificación oficial vigente con fotografía",
                "Documento relacionado con el préstamo, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Contrato de obra a precio alzado" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Permuta" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Prenda" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que es un derecho disponible",
                "Contrato",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Prestación de servicios profesionales" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Prestación de servicios técnicos" => [
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Documento para acreditar disponibilidad y la legitimación",
                "Otro"
            ],
            "Responsabilidad civil" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que sea un derecho disponible",
                "Peritaje, en su caso",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Seguros" => [
                "Identificación oficial vigente con fotografía",
                "Póliza",
                "Factura del bien mueble o inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Servidumbre" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Sociedades" => [
                "Identificación oficial vigente con fotografía",
                "Acta Constitutiva",
                "Actas de asamblea",
                "Contrato, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Otro"
            ],
            "Otros" => []
        ];

        foreach ($documentos as $tipo => $docs) {
            foreach ($docs as $nombre) {
                DB::table('cat_documentos_civil')->insert([
                    'tipo'       => $tipo,
                    'nombre'     => $nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
