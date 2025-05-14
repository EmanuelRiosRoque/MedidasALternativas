<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\PersonaMoral;
use App\Models\PersonaFisica;
use Livewire\WithFileUploads;
use App\Models\PersonaFamiliar;
use App\Models\PersonaSolicitud;
use App\Models\Solicitante;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ConvenioTraits\HandleDocumentos;
use App\Traits\ConvenioTraits\HandleValidaciones;
use App\Traits\ConvenioTraits\HandleArreglosLogicos;
use App\Traits\ConvenioTraits\HandleUpdatedConvenio;
use App\Traits\ConvenioTraits\HandleCrudLogicoPersonas;
use App\Traits\ConvenioTraits\HandleAutoCompletarDomicilio;

class Convenio extends Component
{
    use WithFileUploads;
	// Funcion para auto completar domicilio
	use HandleAutoCompletarDomicilio;

	// Validaciones por pasos
	use HandleValidaciones;

	//Documentos
	use HandleDocumentos;

	// Actualizaciones logicos
	use HandleUpdatedConvenio;

	// Crud-logico para solicitante y invitados
	use HandleCrudLogicoPersonas;

	// Funciones para crear / eliminar telefonos y correos
	use HandleArreglosLogicos;

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
	public $oficio;
	public $cual_otro;

	// Fisica
	public $acudiran_juntos;
	public $formato_privacidad;
	public $representante;
	public string $nombre_solicitante = '';
	public string $apellido_p_solicitante = '';
	public string $apellido_m_solicitante = '';
	public string $nombre_representante = '';
	public string $apellido_p_representante = '';
	public string $apellido_m_representante = '';
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

	public function consulta() {
		$solicitud = Solicitud::with([
			'personas.personaFisica',
			'personas.personaMoral',
			'personas.personaFamiliar',
		])->findOrFail(12);
		
		$extraerPersona = fn($rel) => match ($rel->tipo_persona) {
			'fisica' => $rel->personaFisica,
			'moral' => $rel->personaMoral,
			'familiar' => $rel->personaFamiliar,
			default => null,
		};
		
		$respuesta = [
			'datosGenerales' => $solicitud->only([
				'id', 'modalidad', 'materia', 'derivado_canalizado',
				'numero_ticket', 'institucion', 'oficio', 'cual_otro'
			]),
			'solicitantes' => $solicitud->personas
				->where('rol', 'solicitante')
				->map($extraerPersona)
				->filter()
				->values(),
			'invitados' => $solicitud->personas
				->where('rol', 'invitado')
				->map($extraerPersona)
				->filter()
				->values(),
		];
		
		dd($respuesta);
		
	}

	public function guardado()
	{

		// $this->consulta();

		// dd($this->solicitanteArray);


		// 1. Crear una nueva solicitud de prueba (para asegurarnos de tener un ID válido)
		$solicitud = Solicitud::create([
			"modalidad" => $this->modalidad,
			"materia" => $this->materia,
			"derivado_canalizado" => $this->derivado_canalizado,
			"numero_ticket" => $this->numero_ticket,
			"institucion" => $this->institucion,
			"oficio" => "oficio",
			"cual_otro" => $this->cual_otro
		]);

		 // 2. Procesar solicitantes
		 foreach ($this->solicitanteArray as $datos) {
			// dd($datos);
			$this->guardarPersonaRelacionada($solicitud->id, $datos, 'solicitante');
		}
		
		foreach ($this->invitadoArray as $datos) {
			$this->guardarPersonaRelacionada($solicitud->id, $datos, 'invitado');
		}
		
	

		//  5. Confirmación de que todo fue guardado correctamente
		dd("Guardado");
	}

	protected function guardarPersonaRelacionada($solicitudId, $datos, string $rol = 'solicitante')
	{
		$tipoPersona = null;

    if ($this->materia === "mercantil") {
        $tipoPersona = $datos['persona']; // 'fisica' o 'moral'
        $datosPersona = [
            'tipo' => $tipoPersona,
            'nombre' => $datos['nombre'] ?? '',
            'apellido_p' => $datos['apellido_p'] ?? null,
            'apellido_m' => $datos['apellido_m'] ?? null,
            'sexo' => $datos['sexo'] ?? null,
            'edad' => $datos['edad'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'escolaridad' => $datos['escolaridad'] ?? null,
            'ocupacion' => $datos['ocupacion'] ?? null,
            'nacionalidad' => $datos['nacionalidad'] ?? null,
            'tipo_domicilio' => $datos['tipo_domicilio'] ?? null,
            'calle' => $datos['calle'] ?? null,
            'colonia' => $datos['colonia'] ?? null,
            'municipio' => $datos['municipio'] ?? null,
            'entidad_federativa' => $datos['entidad_federativa'] ?? null,
            'cp' => $datos['cp'] ?? null,
            'rfc' => $datos['rfc'] ?? null,
            'razon_social' => $datos['razon_social'] ?? null,
            'instrumento' => $datos['instrumento'] ?? null,
            'fecha_instrumento' => $datos['fecha_instrumento'] ?? null,
        ];
    } else {
        $tipoPersona = 'familiar';

        $datosPersona = [
            'tipo' => $tipoPersona,
            'nombre' => $datos['nombre'] ?? '',
            'apellido_p' => $datos['apellido_p'] ?? null,
            'apellido_m' => $datos['apellido_m'] ?? null,
            'sexo' => $datos['sexo'] ?? null,
            'edad' => $datos['edad'] ?? null,
            'escolaridad' => $datos['escolaridad'] ?? null,
            'ocupacion' => $datos['ocupacion'] ?? null,
            'tipo_domicilio' => $datos['tipo_domicilio'] ?? null,
            'calle' => $datos['calle'] ?? null,
            'colonia' => $datos['colonia'] ?? null,
            'municipio' => $datos['municipio'] ?? null,
            'entidad_federativa' => $datos['entidad_federativa'] ?? null,
            'cp' => $datos['cp'] ?? null,
            'estado_civil' => $datos['estado_civil'] ?? null,
        ];
    }
    
		Solicitante::create($datosPersona);
	}


	protected function guardarPersonaRelacionadaOld($solicitudId, $datos, string $rol = 'solicitante')
	{
		$tipoPersona = null; // inicializar

		if ($this->materia === "mercantil") {
			$tipoPersona = $datos['persona']; // 'fisica' o 'moral'

			if ($tipoPersona === 'fisica') {
				$datosNormalizados = [
					'nombre' => $datos['nombre'] ?? '',
					'apellido_paterno' => $datos['apellido_p'] ?? null,
					'apellido_materno' => $datos['apellido_m'] ?? null,
					'sexo' => $datos['sexo'] ?? null,
					'edad' => $datos['edad'] ?? null,
					'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
					'escolaridad' => $datos['escolaridad'] ?? null,
					'ocupacion' => $datos['ocupacion'] ?? null,
					'nacionalidad' => $datos['nacionalidad'] ?? null,
					'tipo_domicilio' => $datos['tipo_domicilio'] ?? null,
					'calle' => $datos['calle'] ?? null,
					'colonia' => $datos['colonia'] ?? null,
					'municipio' => $datos['municipio'] ?? null,
					'entidad_federativa' => $datos['entidad_federativa'] ?? null,
					'cp' => $datos['cp'] ?? null,
					'rfc' => $datos['rfc'] ?? null,
				];

				$persona = PersonaFisica::create($datosNormalizados);
			} else {
				$datosMoral = [
					'razon_social' => $datos['razon_social'] ?? 'SIN RAZÓN SOCIAL',
					'rfc' => $datos['rfc'] ?? null,
					'instrumento' => $datos['instrumento'] ?? null,
					'fecha_instrumento' => $datos['fecha_instrumento'] ?? null,
					'tipo_domicilio' => $datos['tipo_domicilio'] ?? null,
					'calle' => $datos['calle'] ?? null,
					'colonia' => $datos['colonia'] ?? null,
					'municipio' => $datos['municipio'] ?? null,
					'entidad_federativa' => $datos['entidad_federativa'] ?? null,
					'cp' => $datos['cp'] ?? null,
				];

				$persona = PersonaMoral::create($datosMoral);
			}
		} else {
			$tipoPersona = 'familiar'; // ⚠️ Se asigna manualmente

			$datosFamiliar = [
				'nombre' => $datos['nombre'] ?? '',
				'apellido_paterno' => $datos['apellido_p'] ?? null,
				'apellido_materno' => $datos['apellido_m'] ?? null,
				'sexo' => $datos['sexo'] ?? null,
				'edad' => $datos['edad'] ?? null,
				'escolaridad' => $datos['escolaridad'] ?? null,
				'ocupacion' => $datos['ocupacion'] ?? null,
				'tipo_domicilio' => $datos['tipo_domicilio'] ?? null,
				'calle' => $datos['calle'] ?? null,
				'colonia' => $datos['colonia'] ?? null,
				'municipio' => $datos['municipio'] ?? null,
				'entidad_federativa' => $datos['entidad_federativa'] ?? null,
				'cp' => $datos['cp'] ?? null,
				'estado_civil' => $datos['estado_civil'] ?? null,
			];

			$persona = PersonaFamiliar::create($datosFamiliar);
		}

		PersonaSolicitud::create([
			'solicitud_id' => $solicitudId,
			'persona_id' => $persona->id,
			'tipo_persona' => $tipoPersona,
			'rol' => $rol,
		]);
		
	}



	public function save () {
		return Redirect::route('pre-mediacion.index')
			->success('Convenio registrado correctamente !'); 
	}
}
