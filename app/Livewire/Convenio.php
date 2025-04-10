<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ConvenioTraits\HandleDocumentos;
use App\Traits\ConvenioTraits\HandleUpdatedConvenio;
use App\Traits\ConvenioTraits\HandleCrudLogicoPersonas;

class Convenio extends Component
{
    use WithFileUploads;

	public int $tab = 1;
	// Input Radios
	public  $modalidad;
	public  $materia;
	public  $tipo_convenio;

	//Datos por tipo de usuario
	public array $solicitanteArray = [];
	public array $invitadoArray = [];
	
	// Generales
	public $persona;
	public $derivado_canalizado;
	public string $como_se_entero = '';
	public string $numero_ticket = '';
	public $doc_representante;
	// Fisica
	public $representante;
	public string $nombre_solicitante = '';
	public string $sexo_solicitante = '';
	public string $edad_solicitante = '';
	public string $fecha_nacimiento_solicitante = '';
	public string $escolaridad_solicitante = '';
	public string $ocupacion_solicitante = '';
	public string $nacionalidad_solicitante= '';
	public string $tipo_domicilio_solicitante = '';
	public string $calle_solicitante = '';
	public string $colonia_solicitante = '';
	public string $municipio_solicitante = '';
	public string $entidad_federativa_solicitante = '';
	public string $correo_solicitante = '';
    public string $cp_solicitante = '';
	// Moral
	public string $razon_social_solicitante = '';
	public string $rfc_solicitante= '';
	public string $instrumento_solicitante = '';
	public string $fecha_instrumento_solicitante = '';
	public string $telefono_solicitante = '';

	// Familiar
	public string $domicilio_solicitante = '';
	public string $estado_civil_solicitante = '';

	// Documento
	public $identificacion;
	public $acta_notarial;
	public $acta_de_nacimiento;
	public $resolucion_judicial;

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

    public function mount()
    {
        $this->tiposDisponibles = array_keys($this->documentosOpcionalesPorTipo());
        $this->temasFamiliaresDisponibles = array_keys($this->documentosPorTemaFamiliar());

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

    //Documentos
    use HandleDocumentos;

	// Actualizaciones logicos
	use HandleUpdatedConvenio;

	// Crud-logico para solicitante y invitados
	use HandleCrudLogicoPersonas;
	

	public function save () {
		return Redirect::route('pre-mediacion.index')
			->success('Convenio registrado correctamente !'); 
	}
	

	public function render()
	{
		return view('livewire.convenio');
	}
}
