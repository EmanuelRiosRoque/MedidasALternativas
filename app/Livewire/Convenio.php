<?php

namespace App\Livewire;

use App\Models\SepomexColonia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ConvenioTraits\HandleDocumentos;
use App\Traits\ConvenioTraits\HandleUpdatedConvenio;
use App\Traits\ConvenioTraits\HandleCrudLogicoPersonas;
use App\Traits\ConvenioTraits\HandleValidaciones;

class Convenio extends Component
{
    use WithFileUploads;

	public int $tab = 1;
	// Input Radios
	public  $modalidad;
	public  $materia;
	public  $tipo_convenio;

	public $codigo_postal = '';
	/** @var \Illuminate\Support\Collection|\App\Models\SepomexColonia[] */
    public $colonias = [];
    public $colonia = '';
    public $estado = '';
    public $municipio = '';

	//Datos por tipo de usuario
	public array $solicitanteArray = [];
	public array $invitadoArray = [];
	
	// Generales
	public $persona;
	public $persona_invitado;
	public $derivado_canalizado;
	public string $como_se_entero = '';
	public string $numero_ticket = '';
	public $doc_representante;
	public $institucion= '';
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
    public $cp_solicitante = '';
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


	public $entidades;
	public $ocupaciones;
	public $escolaridades;
	public $mediosSolicitante;
	public $mediosInvitado;
	
    public function mount()
    {
        $this->tiposDisponibles = array_keys($this->documentosOpcionalesPorTipo());
        $this->temasFamiliaresDisponibles = array_keys($this->documentosPorTemaFamiliar());
		$this->entidades = $this->entidadesFederativas();
		$this->ocupaciones = $this->ocupaciones();
		$this->escolaridades = $this->escolaridades();
		$this->mediosSolicitante = $this->difucionSolicitante();
		$this->mediosInvitado = $this->difucionInvitado();
    }
	
	public function updatedCpSolicitante()
    {
        $this->colonias = SepomexColonia::where('codigo_postal', $this->cp_solicitante)
            ->get();

        if ($this->colonias->isNotEmpty()) {
            $this->entidad_federativa_solicitante = $this->colonias->first()->estado;
            $this->municipio_solicitante = $this->colonias->first()->municipio;
        } else {
            $this->entidad_federativa_solicitante = '';
            $this->municipio_solicitante = '';
        }

        $this->colonia = '';
    }

	public function cambiarTab($nuevoTab)
	{
		// Limpiar solicitante si sales del tab 2
		if ($this->tab === 2) {
			$this->limpiarCamposPersona();
		}
	
		// Limpiar invitado si sales del tab 3
		if ($this->tab === 3) {
			$this->limpiarCamposPersona(); // si manejas campos distintos, crea otra función
		}
	
		$this->tab = $nuevoTab;
	}
	

	// Validaciones por pasos
	use HandleValidaciones;
    
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
