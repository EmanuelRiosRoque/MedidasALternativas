<?php

namespace App\Traits\ConvenioTraits;

trait HandleDocumentos
{

    public function documentosOpcionalesPorTipo(): array
    {
        return [
            "Arrendamiento" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Escritura del inmueble para acreditar disponibilidad",
                "Recibo de rentas, en su caso",
                "Recibo de pago de servicios, en su caso",
                "Poder notarial en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta de matrimonio, en caso de sociedad conyugal"
            ],
            "Comisión mercantil" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas o recibos",
                "Acta constitutiva"
            ],
            "Comodato" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Contrato de comodato",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal",
                "Acta de matrimonio, en caso de sociedad conyugal"
            ],
            "Compraventa" => [
                "Identificación oficial vigente con fotografía",
                "Escritura del inmueble, para acreditar disponibilidad",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal"
            ],
            "Condominio" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Nombramiento de Administrador extendido por PROSOC",
                "Recibo de cuotas condominales, en su caso",
                "Acta de matrimonio, en caso de sociedad conyugal"
            ],
            "Copropiedad" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble"
            ],
            "Derecho de habitación" => [
                "Identificación oficial vigente con fotografía",
                "Escritura del inmueble, para acreditar disponibilidad",
                "Contrato",
                "Recibo de pago de rentas, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                "Acta de matrimonio, en caso de sociedad conyugal"
            ],
            "Derecho de autor" => [
                "Identificación oficial vigente con fotografía",
                "El registro de la obra ante el INDAUTOR"
            ],
            "Donación" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Certificado de libertad de gravamen con fecha de expedición no mayor a seis meses",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Acta de matrimonio, en caso de sociedad conyugal"
            ],
            "Fideicomiso" => [
                "Identificación oficial vigente con fotografía",
                "Contrato con cláusulas relacionadas con el fideicomiso",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Franquicia" => [
                "Identificación oficial vigente con fotografía",
                "Contrato de adquisición de franquicia",
                "Facturas o recibos",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Hipoteca" => [
                "Identificación oficial vigente con fotografía",
                "El contrato de hipoteca",
                "Pagos relacionados con la hipoteca",
                "Certificado de libertad de Gravamen, con fecha de expedición no mayor a seis meses",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Nombramiento de albacea, tratándose de sucesiones"
            ],
            "Hospedaje" => [
                "Identificación oficial vigente con fotografía",
                "Reservación",
                "Contrato, en su caso",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Mutuo con interés" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Pagaré, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Mutuo simple" => [
                "Identificación oficial vigente con fotografía",
                "Documento relacionado con el préstamo, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Contrato de obra a precio alzado" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Permuta" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Prenda" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que es un derecho disponible",
                "Contrato",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Prestación de servicios profesionales" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Prestación de servicios técnicos" => [
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Documento para acreditar disponibilidad y la legitimación"
            ],
            "Responsabilidad civil" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que sea un derecho disponible",
                "Peritaje, en su caso",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Seguros" => [
                "Identificación oficial vigente con fotografía",
                "Póliza",
                "Factura del bien mueble o inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Servidumbre" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ],
            "Sociedades" => [
                "Identificación oficial vigente con fotografía",
                "Acta Constitutiva",
                "Actas de asamblea",
                "Contrato, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales"
            ]
        ];
    }

    public function documentosPorTemaFamiliar(): array
    {
        return [
            'Pensión alimenticia' => [
                'Acta de nacimiento del menor',
                'Comprobante de ingresos del deudor alimentario',
                'Identificación oficial',
                'Recibo de nomina',
            ],
            'Guarda / Custodia de los hijos' => [
                'Acta de nacimiento del menor',
                'Si existió juicio sobre custodia, copias simples del mismo',
            ],
            'Visitas supervisadas' => [
                'Acta de nacimiento del menor',
                'Si existió juicio, copias simples del mismo',
            ],
            'Cuestiones patrimoniales' => [
                'Documentos que acrediten propiedad (actas, escrituras, etc)',
                'Acta de matrimonio',
            ],
            'Derivados de la sociedad conyugal' => [
                'Acta de matrimonio',
                'Juicio previo (si existe), copias simples',
            ],
            'Elaboración de convenio' => [
                'Identificación oficial de ambos',
            ],
            'Modificación de términos de resolución judicial' => [
                'Resolución judicial que se desea modificar',
            ],
            'Reconocimiento del vínculo' => [
                'Identificación de ambos',
            ],
            'Separación' => [
                'Acta de matrimonio',
                'Documento de separación si existe',
            ],
        ];
    }

    public function entidadesFederativas(): array 
    {
        return [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Ciudad de México',
            'Coahuila',
            'Colima',
            'Durango',
            'Estado de México',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'Michoacán',
            'Morelos',
            'Nayarit',
            'Nuevo León',
            'Oaxaca',
            'Puebla',
            'Querétaro',
            'Quintana Roo',
            'San Luis Potosí',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatán',
            'Zacatecas',
        ];
    }

    public function ocupaciones(): array 
    {
        return [
            'Comerciante',
            'Desempleado',
            'Empleado',
            'Estudiante',
            'Hogar',
            'Jubilado',
            'Oficio independiente',
            'Profesionista independiente',
            'Servidor Público',
            'Otra',
        ];
    }

    public function escolaridades(): array 
    {
        return [
            'Primaria inconclusa',
            'Primaria terminada',
            'Secundaria inconclusa',
            'Secundaria terminada',
            'Media superior',
            'Carrera técnica inconclusa',
            'Carrera técnica terminada',
            'Carrera comercial inconclusa',
            'Carrera comercial terminada',
            'Licenciatura inconclusa',
            'Licenciatura terminada',
            'Maestría inconclusa',
            'Maestría terminada',
            'Doctorado inconcluso',
            'Doctorado terminado',
            'Otra',
        ];
    }

    public function difucionSolicitante(): array
    {
        return [
            'Alcaldía',
            'Cartel',
            'Comisión de Derechos Humanos de la CDMX',
            'Comisión Nacional de Derechos Humanos',
            'Fiscalía de la CDMX',
            'Folleto',
            'Juzgado (tipo)',
            'Locatel',
            'Consejo Ciudadano',
            'Internet',
            'Persona',
            'Periódico',
            'Radio',
            'Televisión',
            'Otro',
        ];
    }

    public function difucionInvitado(): array
    {
        return [
            'Alcaldía',
            'Cartel',
            'Comisión de Derechos Humanos de la CDMX',
            'Comisión Nacional de Derechos Humanos',
            'Fiscalía de la CDMX',
            'Folleto',
            'Juzgado (tipo)',
            'Locatel',
            'Consejo Ciudadano',
            'Internet',
            'Invitación CJA',
            'Persona',
            'Periódico',
            'Radio',
            'Televisión',
        ];
    }
    

    public function updatedTipo($value)
    {
        $this->documentosOpcionales = $this->documentosOpcionalesPorTipo()[$value] ?? [];
        $this->documentoSeleccionado = '';
    }
    
    public function updatedDocumentoSeleccionado($value)
    {
        if (!in_array($value, $this->documentosCargados)) {
            $this->documentosCargados[] = $value;
        }
        $this->documentoSeleccionado = '';
    }
    
    public function updatedTemaFamiliar($value)
    {
        $this->documentosFamiliarOpcionales = $this->documentosPorTemaFamiliar()[$value] ?? [];
        $this->documentosFamiliarSeleccionado = '';
    }
    
    public function updatedDocumentosFamiliarSeleccionado($value)
    {
        if (!in_array($value, $this->documentosFamiliaresCargados)) {
            $this->documentosFamiliaresCargados[] = $value;
        }
        $this->documentosFamiliarSeleccionado = '';
    }

    public function guardarArchivos()
    {
        $documentosConArchivos = [];

        foreach ($this->documentosCargados as $index => $documento) {
            // Verifica si se cargó algún archivo para este documento
            if (isset($this->archivosSubidos[$index]) && !empty($this->archivosSubidos[$index])) {
                $documentosConArchivos[] = [
                    'documento' => $documento,
                    'archivo' => $this->archivosSubidos[$index]
                ];
            } else {
                $documentosConArchivos[] = [
                    'documento' => $documento,
                    'archivo' => 'No se subió archivo'
                ];
            }
        }

        dd($documentosConArchivos);
    }

    public function eliminarDocumentoFamiliar($doc)
    {
        if (($key = array_search($doc, $this->documentosFamiliaresCargados)) !== false) {
            unset($this->documentosFamiliaresCargados[$key]);
            unset($this->archivosFamiliaresSubidos[$key]);
            $this->documentosFamiliaresCargados = array_values($this->documentosFamiliaresCargados);
            $this->archivosFamiliaresSubidos = array_values($this->archivosFamiliaresSubidos);
        }
    }


    public function eliminarDocumento($doc)
    {
        if (($key = array_search($doc, $this->documentosCargados)) !== false) {
            unset($this->documentosCargados[$key]);
            unset($this->archivosSubidos[$key]);
            $this->documentosCargados = array_values($this->documentosCargados);
            $this->archivosSubidos = array_values($this->archivosSubidos);
        }
    }

}
