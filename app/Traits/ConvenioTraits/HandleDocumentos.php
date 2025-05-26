<?php

namespace App\Traits\ConvenioTraits;
use Illuminate\Support\Facades\File;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;

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
                "Acta de matrimonio, en caso de sociedad conyugal",
                'Otro'
            ],
            "Comisión mercantil" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas o recibos",
                "Acta constitutiva",
                'Otro'
            ],
            "Comodato" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Contrato de comodato",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal",
                "Acta de matrimonio, en caso de sociedad conyugal",
                'Otro'
            ],
            "Compraventa" => [
                "Identificación oficial vigente con fotografía",
                "Escritura del inmueble, para acreditar disponibilidad",
                "Acta constitutiva, para el caso de personas morales",
                "Poder notarial, en caso de representante legal",
                'Otro'
            ],
            "Condominio" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Nombramiento de Administrador extendido por PROSOC",
                "Recibo de cuotas condominales, en su caso",
                "Acta de matrimonio, en caso de sociedad conyugal",
                'Otro'
            ],
            "Copropiedad" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble",
                'Otro'
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
                'Otro'
            ],
            "Derecho de autor" => [
                "Identificación oficial vigente con fotografía",
                "El registro de la obra ante el INDAUTOR",
                'Otro'
            ],
            "Donación" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble, para acreditar disponibilidad",
                "Certificado de libertad de gravamen con fecha de expedición no mayor a seis meses",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales",
                "Acta de matrimonio, en caso de sociedad conyugal",
                'Otro'
            ],
            "Fideicomiso" => [
                "Identificación oficial vigente con fotografía",
                "Contrato con cláusulas relacionadas con el fideicomiso",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Franquicia" => [
                "Identificación oficial vigente con fotografía",
                "Contrato de adquisición de franquicia",
                "Facturas o recibos",
                "Poder notarial del representante legal",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Hipoteca" => [
                "Identificación oficial vigente con fotografía",
                "El contrato de hipoteca",
                "Pagos relacionados con la hipoteca",
                "Certificado de libertad de Gravamen, con fecha de expedición no mayor a seis meses",
                "Acta de matrimonio, en caso de sociedad conyugal",
                "Nombramiento de albacea, tratándose de sucesiones",
                'Otro'
            ],
            "Hospedaje" => [
                "Identificación oficial vigente con fotografía",
                "Reservación",
                "Contrato, en su caso",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Mutuo con interés" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Pagaré, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Mutuo simple" => [
                "Identificación oficial vigente con fotografía",
                "Documento relacionado con el préstamo, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Contrato de obra a precio alzado" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Permuta" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc.",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Prenda" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que es un derecho disponible",
                "Contrato",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Prestación de servicios profesionales" => [
                "Identificación oficial vigente con fotografía",
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Prestación de servicios técnicos" => [
                "Contrato",
                "Facturas, recibos, notas, etc., en su caso",
                "Documento para acreditar disponibilidad y la legitimación",
                'Otro'
            ],
            "Responsabilidad civil" => [
                "Identificación oficial vigente con fotografía",
                "Documento para acreditar que sea un derecho disponible",
                "Peritaje, en su caso",
                "Facturas, recibos, notas, etc., en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Seguros" => [
                "Identificación oficial vigente con fotografía",
                "Póliza",
                "Factura del bien mueble o inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Servidumbre" => [
                "Identificación oficial vigente con fotografía",
                "Escrituras del inmueble",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            "Sociedades" => [
                "Identificación oficial vigente con fotografía",
                "Acta Constitutiva",
                "Actas de asamblea",
                "Contrato, en su caso",
                "Poder notarial, en caso de representante legal",
                "Nombramiento de albacea, tratándose de sucesiones",
                "Acta constitutiva, para el caso de personas morales",
                'Otro'
            ],
            'Otros' => [
                
            ],
        ];
    }

    public function documentosPorTemaFamiliar(): array
    {
        return [
            'Pensión alimenticia' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Oficio de descuento de pensión alimenticia',
                'Otro'
            ],
            'Guarda y Custodia' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Resolución judicial previa',
                'Otro'
            ],
            'Visitas y convivencias' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Resolución judicial previa',
                'Otro'
            ],
            'Cuestiones patrimoniales derivadas de juicio sucesorio' => [
                'Resolucion judicial',
                'Acta notarial',
                'Otro'
            ],
            'Derivados de la disolución de la sociedad conyugal' => [
                'Acta de matrimonio',
                'Resolucion judicial',
                'Documentos a través de los cuales acrediten la propiedad de los bienes',
                'Otro'
            ],
            'Elaboración de convenio regulador de divorcio o separación' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Documentos a través de los cuales acrediten la propiedad de los bienes de la sociedad conyugal o para compensación de bienes',
                'Otro'
            ],

            'Modificación de los términos de Resolución judicial' => [
                'Acta de registro civil: matrimonio, nacimiento',
                'Recibo de nómina',
                'Resolución judicial previa',
                'Oficio de descuento de pensión alimenticia',
                'Otro'
            ],

            // 'Modificación de los términos de Resolución judicial' => [
            //     'Acta de registro civil: matrimonio, nacimiento',
            //     'Recibo de nómina',
            //     'Resolución judicial previa',
            //     'Oficio de descuento de pensión alimenticia',
            // ],

            'Crisis de la convivencia' => [
                
            ],

            'Otros' => [
                
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
            'Media superior inconclusa',
            'Media superior terminada',
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
            'Juzgado',
            'Centro de Atención a las Mujeres',
            'Locatel',
            'Consejo Ciudadano',
            'Internet',
            'Invitación CJA',
            'Por otra persona',
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

    public function guardarArchivos(): array
    {
        $documentosConArchivos = [];

        foreach ($this->documentosCargados as $index => $documentoTipo) {
            $archivo = $this->archivosSubidos[$index][0] ?? null;

            if ($archivo && isset($archivo['path']) && file_exists($archivo['path'])) {
                // Generar nombre único
                $nombreOriginal = $archivo['name'];
                $nuevoNombre = uniqid() . '_' . $nombreOriginal;
                $destino = 'documentos';

                // Guardar archivo en public/documentos/
                $rutaFinal = Storage::disk('public')->putFileAs(
                    $destino,
                    new HttpFile($archivo['path']),
                    $nuevoNombre
                );

                $rutaPublica = 'storage/' . $rutaFinal;

                // Guardar información del documento para base de datos
                $documentosConArchivos[] = [
                    'documento'        => $documentoTipo,
                    'nombre_original'  => $nombreOriginal,
                    'ruta'             => $rutaPublica,
                    'extension'        => $archivo['extension'] ?? pathinfo($nombreOriginal, PATHINFO_EXTENSION),
                    'size'             => $archivo['size'] ?? null,
                ];
            }
        }

        return $documentosConArchivos;
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
