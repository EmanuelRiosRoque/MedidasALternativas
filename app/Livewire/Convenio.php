<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\{DB, Storage, Redirect};

use Masmerise\Toaster\Toaster;

use App\Models\{
    Solicitud,
    Documento,
    DocumentoSolicitud
};

use App\Models\Catalogos\{
    CatDocumentoCivil,
    CatDocumentoFamiliar
};

use App\Traits\ConvenioTraits\{
    HandleDocumentos,
    HandleArreglosLogicos,
    HandleUpdatedConvenio,
    HandleCrudLogicoPersonas,
    HandleAutoCompletarDomicilio,
    HandlePersonas
};

class Convenio extends Component
{
    use WithFileUploads;

    // Función para auto completar domicilio
    use HandleAutoCompletarDomicilio;

    // Documentos
    use HandleDocumentos;

    // Actualizaciones lógicas
    use HandleUpdatedConvenio;

    // Crud lógico para solicitante e invitados
    use HandleCrudLogicoPersonas;

    // Funciones para crear / eliminar teléfonos y correos
    use HandleArreglosLogicos;

    // manejo de personas (payload + contactos + docs)
    use HandlePersonas;

    public int $tab = 1;

    // Input Radios
    public $modalidad;
    public $materia;
    public $tipo_convenio;

    public $codigo_postal = '';
    /** @var \Illuminate\Support\Collection|\App\Models\SepomexColonia[] */
    public $colonias = [];
    public $colonia = '';
    public $estado = '';
    public $municipio = '';

    // Datos por tipo de usuario
    public array $solicitanteArray = [];
    public array $invitadoArray = [];

    // Generales
    public $persona;
    public $persona_invitado;
    public $derivado_canalizado;
    public $como_se_entero = '';
    public string $numero_ticket = '';
    public $doc_representante = [];
    public $institucion = '';
    public $oficio;
    public $cual_otro;

    // Física
    public $acudiran_juntos;
    public $formato_privacidad;
    public $representante = 0;
    public $nombre_solicitante = '';
    public $apellido_p_solicitante = '';
    public $apellido_m_solicitante = '';
    public $nombre_representante = '';
    public $apellido_p_representante = '';
    public $apellido_m_representante = '';
    public $sexo_solicitante = '';
    public $edad_solicitante = '';
    public $fecha_nacimiento_solicitante = '';
    public $escolaridad_solicitante = '';
    public $ocupacion_solicitante = '';
    public $nacionalidad_solicitante = '';
    public $tipo_domicilio_solicitante = '';
    public $calle_solicitante = '';
    public $municipio_solicitante = '';
    public $entidad_federativa_solicitante = '';
    public $correo_solicitante = '';
    public $cp_solicitante = '';

    // Moral
    public $razon_social_solicitante = '';
    public $rfc_solicitante = '';
    public $instrumento_solicitante = '';
    public $fecha_instrumento_solicitante = '';
    public $telefono_solicitante = '';

    // Familiar
    public $domicilio_solicitante = '';
    public $estado_civil_solicitante = '';

    // Documento
    public $identificacion;
    public $acta_notarial;
    public $acta_de_nacimiento;
    public $resolucion_judicial;
    public $titulo_credito;
    public $formato_Privacidad;

    public $detalleSeleccionado = [];
    public $mostrarModal = false;

    public bool $modoEdicion = false;
    public ?int $indiceEdicion = null;

    public $tipo = '';
    public $documentoSeleccionado = '';
    public $documentosOpcionales = [];
    public $tiposDisponibles = [];
    public $archivosSubidos = [];
    public $documentosCargados = [];

    public $temaFamiliar = '';
    public $documentosFamiliarSeleccionado = '';
    public $documentosFamiliarOpcionales = [];
    public $temasFamiliaresDisponibles = [];
    public $documentosFamiliaresCargados = [];
    public $archivosFamiliaresSubidos = [];

    public $entidades;
    public $ocupaciones;
    public $escolaridades;
    public $mediosSolicitante;
    public $mediosInvitado;

    public string $correo_temp = '';
    public string $telefono_temp = '';
    public array $correos = [];
    public array $telefonos = [];

    public function mount($id = null)
    {
        $this->tiposDisponibles           = CatDocumentoCivil::tipos();
        $this->temasFamiliaresDisponibles = CatDocumentoFamiliar::tipos();

        // (Opcional) cachear en el trait para performance
        $this->ocupaciones   = $this->ocupaciones();
        $this->escolaridades = $this->escolaridades();
        $this->mediosInvitado= $this->mediosDifusion();
    }

    public function updated($propertyName)
    {
        $this->resetValidation([$propertyName]);
    }

    public function cambiarTab($nuevoTab)
    {
        if ($this->tab === 1 && $nuevoTab !== 1) {
            $rules = [
                'modalidad'           => 'required',
                'materia'             => 'required',
                'derivado_canalizado' => 'required',
            ];

            if ($this->modalidad === 'linea') {
                $rules['numero_ticket'] = 'required';
            }

            if ($this->derivado_canalizado === '1') {
                $rules['institucion'] = 'required';
                $rules['oficio']      = 'required';
            }

            $this->validate($rules);
        }

        if ($this->tab === 2 && $nuevoTab === 3) {
            if (empty($this->solicitanteArray)) {
                Toaster::warning('Debe agregar al menos un solicitante antes de continuar !');
                return;
            }
            $this->validate(['acudiran_juntos' => 'required']);
        }

        if ($this->tab === 3 && $nuevoTab === 4) {
            if (empty($this->invitadoArray)) {
                Toaster::warning('Debe agregar al menos un invitado antes de continuar.');
                return;
            }
        }

        if (in_array($this->tab, [2, 3], true)) {
            $this->limpiarCamposPersona();
        }

        $this->tab = $nuevoTab;
    }

    public function guardado()
    {
        try {
            DB::beginTransaction();

            // 1) Guardar oficio si aplica
            $rutaOficio = $this->guardarDocumentoIndividual(
                $this->oficio,
                'oficio',
                null,
                false
            );

            // 2) Generar folio según materia
            $folio = $this->materia === 'familiar'
                ? $this->generarFolio('CJA', 'MF', siguienteValorSecuencia('familiar'))
                : $this->generarFolio('CJA', 'MCM', siguienteValorSecuencia('civil'));

            // 3) Crear solicitud
            $solicitud = Solicitud::create([
                "modalidad"           => $this->modalidad,
                "acudiran_juntos"     => $this->acudiran_juntos,
                "folio_materia"       => $folio,
                "estatus_id"          => 1,
                "materia"             => $this->materia,
                "derivado_canalizado" => $this->derivado_canalizado,
                "numero_ticket"       => $this->numero_ticket,
                "institucion"         => $this->institucion,
                "oficio"              => $rutaOficio,
                "cual_otro"           => $this->cual_otro
            ]);

            // 4) Personas relacionadas
            foreach ($this->solicitanteArray as $datos) {
                $this->guardarPersonaRelacionada($solicitud->id, $datos, 'solicitante');
            }

            foreach ($this->invitadoArray as $datos) {
                $this->guardarPersonaRelacionada($solicitud->id, $datos, 'invitado');
            }

            // 5) Documentos generales de la solicitud (UNA sola vez)
            foreach ($this->guardarArchivos() as $doc) {
                DocumentoSolicitud::create([
                    'solicitud_id'    => $solicitud->id,
                    'tipo'            => $doc['documento'],
                    'nombre_original' => $doc['nombre_original'],
                    'ruta'            => $doc['ruta'],
                    'extension'       => $doc['extension'],
                    'size'            => $doc['size'],
                ]);
            }

            DB::commit();

            return Redirect::route('solicitudes.lista')
                ->success('Solicitud creada exitosamente !');

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e); // detiene la ejecución y muestra toda la excepción
        }

    }

    protected function guardarDocumentoIndividual($archivo, $tipo, $solicitanteId, $guardarDB = true)
    {
        if (!$archivo || empty($archivo['path']) || !is_file($archivo['path'])) {
            return null;
        }

        $nombreOriginal = $archivo['name'];
        $nuevoNombre    = uniqid('', true) . '_' . $nombreOriginal;
        $destino        = 'documentos';

        $rutaFinal   = Storage::disk('public')->putFileAs(
            $destino,
            new HttpFile($archivo['path']),
            $nuevoNombre
        );

        $rutaPublica = 'storage/' . $rutaFinal;

        if ($guardarDB) {
            Documento::create([
                'solicitante_id'  => $solicitanteId,
                'tipo'            => $tipo,
                'nombre_original' => $nombreOriginal,
                'ruta'            => $rutaPublica,
                'extension'       => $archivo['extension'] ?? pathinfo($nombreOriginal, PATHINFO_EXTENSION),
                'size'            => $archivo['size'] ?? null,
            ]);
        }

        return $rutaPublica;
    }

    private function generarFolio(string $prefijo, string $clave, int $folio): string
    {
        $anio = now()->year;
        $folioFormateado = str_pad($folio, 4, '0', STR_PAD_LEFT);

        return ($this->modalidad === 'presencial')
            ? "$prefijo-$clave-$folioFormateado-$anio"
            : "V-$prefijo-$clave-$folioFormateado-$anio";
    }

    public function save ()
    {
        return Redirect::route('pre-mediacion.index')
            ->success('Convenio registrado correctamente !');
    }
}
