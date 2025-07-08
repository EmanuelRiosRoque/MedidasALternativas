<?php

namespace App\Livewire;

use App\Models\Correo;
use Livewire\Component;
use App\Models\Telefono;
use App\Models\Documento;
use App\Models\Solicitud;
use App\Models\Solicitante;

use App\Models\Representante;
use Livewire\WithFileUploads;

use Masmerise\Toaster\Toaster;
use App\Models\DocumentoSolicitud;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ConvenioTraits\HandleDocumentos;
use App\Traits\ConvenioTraits\HandleArreglosLogicos;
use App\Traits\ConvenioTraits\HandleUpdatedConvenio;
use App\Traits\ConvenioTraits\HandleCrudLogicoPersonas;
use App\Traits\ConvenioTraits\HandleAutoCompletarDomicilio;

class Convenio extends Component
{
    use WithFileUploads;
	
	// Funcion para auto completar domicilio
	use HandleAutoCompletarDomicilio;

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
	public $como_se_entero = '';
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
	public $fecha_nacimiento_solicitante = '';
	public string $escolaridad_solicitante = '';
	public string $ocupacion_solicitante = '';
	public  $nacionalidad_solicitante= '';
	public string $tipo_domicilio_solicitante = '';
	public string $calle_solicitante = '';
	public string $municipio_solicitante = '';
	public string $entidad_federativa_solicitante = '';
	public string $correo_solicitante = '';
    public $cp_solicitante = '';
	// Moral
	public $razon_social_solicitante = '';
	public $rfc_solicitante= '';
	public $instrumento_solicitante = '';
	public $fecha_instrumento_solicitante = '';
	public string $telefono_solicitante = '';

	// Familiar
	public  $domicilio_solicitante = '';
	public  $estado_civil_solicitante = '';

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

  	public function mount($id = null)
	{
		$this->tiposDisponibles = array_keys($this->documentosOpcionalesPorTipo());
		$this->temasFamiliaresDisponibles = array_keys($this->documentosPorTemaFamiliar());
		$this->entidades = $this->entidadesFederativas();
		$this->ocupaciones = $this->ocupaciones();
		$this->escolaridades = $this->escolaridades();
		$this->mediosSolicitante = $this->difucionSolicitante();
		$this->mediosInvitado = $this->difucionInvitado();
	}
	
	public function updated($propertyName)
	{
		$this->resetValidation([$propertyName]);
	}

	public function cambiarTab($nuevoTab)
	{

		if ($this->tab === 1 && $nuevoTab !== 1) {
			$rules = [
				'modalidad' => 'required',
				'materia' => 'required',
				'derivado_canalizado' => 'required',
			];

			// Validar el número de ticket si la modalidad es línea
			if ($this->modalidad === 'linea') {
				$rules['numero_ticket'] = 'required|string|max:255'; // ajusta según lo que necesites
			}

			if ($this->derivado_canalizado === '1') {
				$rules['institucion'] = 'required';
				$rules['oficio'] = 'required';
			}

			$this->validate($rules);
		}

		if ($this->tab === 2 && $nuevoTab === 3) {
			$rules = [
				'acudiran_juntos' => 'required',
			];
			if (empty($this->solicitanteArray)) {
				Toaster::warning('Debe agregar al menos un solicitante antes de continuar !');
				return;
			}
			$this->validate($rules);
		}

		if ($this->tab === 3 && $nuevoTab === 4) {
			if (empty($this->invitadoArray)) {
				Toaster::warning('Debe agregar al menos un invitado antes de continuar.');
				return;
			}
		}
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

	public function guardado()
	{
		$rutaOficio = null;

		// Si existe el archivo de oficio, lo guardamos primero
		if (!empty($this->oficio) && !empty($this->oficio['path']) && file_exists($this->oficio['path'])) {
			$rutaOficio = $this->guardarDocumentoIndividual(
				$this->oficio,
				'oficio', // tipo de documento
				null,     // no está ligado a un solicitante, es general
				false     // no lo guardamos en la tabla Documento si no aplica
			);
		}

		//Todo Guardar folios por materia y modalidad (V-Para virtuales P-Presenciales) 
		// $folioFamiliar = siguienteValorSecuencia('familiar'); 
        // $folioCivil = siguienteValorSecuencia('civil');    

		// $folioFamiliarPresencial = $this->generarFolio('CJA', 'MF', $folioFamiliar);
		// $folioCivilPresencial    = $this->generarFolio('CJA', 'MCM', $folioCivil);

		// $folioSeleccionado = $this->materia === 'familiar'
		// ? $folioFamiliarPresencial
		// : $folioCivilPresencial;

		// Crear la solicitud
		$solicitud = Solicitud::create([
			"modalidad" => $this->modalidad,
			// 
			"acudiran_juntos" => $this->acudiran_juntos,
			// "folio_materia" => $folioSeleccionado,
			"estatus_id" => 1,
			"materia" => $this->materia,
			"derivado_canalizado" => $this->derivado_canalizado,
			"numero_ticket" => $this->numero_ticket,
			"institucion" => $this->institucion,
			"oficio" => $rutaOficio,
			"cual_otro" => $this->cual_otro
		]);

		// Procesar personas relacionadas
		foreach ($this->solicitanteArray as $datos) {
			$this->guardarPersonaRelacionada($solicitud->id, $datos, 'solicitante');
		}

		foreach ($this->invitadoArray as $datos) {
			$this->guardarPersonaRelacionada($solicitud->id, $datos, 'invitado');
		}

		return Redirect::route('solicitudes.index')
			->success('Solicitud creada exitosamente !');
	}

	protected function guardarPersonaRelacionada($solicitudId, $datos, string $rol = 'solicitante')
	{
		$tipoPersona = null;
		$solicitante = null;

		// 1. Crear $datosPersona según la materia
		if (in_array($this->materia, ['mercantil', 'civil'])) {
			$tipoPersona = $datos['persona'] ?? 'fisica';

			$datosPersona = [
				'persona'             => $tipoPersona,
				'nombre'              => $datos['nombre'] ?? '',
				'representante'       => $datos['representante'] ?? null,
				'tipo_solicitante'    => $rol,
				'apellido_p'          => $datos['apellido_p'] ?? null,
				'apellido_m'          => $datos['apellido_m'] ?? null,
				'sexo'                => $datos['sexo'] ?? null,
				'edad'                => $datos['edad'] ?? null,
				'fecha_nacimiento'    => !empty($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null,
				'escolaridad'         => $datos['escolaridad'] ?? null,
				'ocupacion'           => $datos['ocupacion'] ?? null,
				'nacionalidad'        => $datos['nacionalidad'] ?? null,
				'tipo_domicilio'      => $datos['tipo_domicilio'] ?? null,
				'calle'               => $datos['calle'] ?? null,
				'colonia'             => $datos['colonia'] ?? null,
				'municipio'           => $datos['municipio'] ?? null,
				'entidad_federativa'  => $datos['entidad_federativa'] ?? null,
				'cp'                  => $datos['cp'] ?? null,
				'rfc'                 => $datos['rfc'] ?? null,
				'razon_social'        => $datos['razon_social'] ?? null,
				'instrumento'         => $datos['instrumento'] ?? null,
				'como_se_entero' 	  => $datos['como_se_entero'] ?? '',
				'fecha_instrumento'   => !empty($datos['fecha_instrumento']) ? $datos['fecha_instrumento'] : null,
				'solicitud_id'        => $solicitudId,
			];
		} else {
			$tipoPersona = 'familiar';

			$datosPersona = [
				'persona'             => $tipoPersona,
				'tipo_solicitante'    => $rol,
				'nombre'              => $datos['nombre'] ?? '',
				'representante'       => $datos['representante'] ?? null,
				'apellido_p'          => $datos['apellido_p'] ?? null,
				'apellido_m'          => $datos['apellido_m'] ?? null,
				'sexo'                => $datos['sexo'] ?? null,
				'edad'                => $datos['edad'] ?? null,
				'escolaridad'         => $datos['escolaridad'] ?? null,
				'ocupacion'           => $datos['ocupacion'] ?? null,
				'tipo_domicilio'      => $datos['tipo_domicilio'] ?? null,
				'calle'               => $datos['calle'] ?? null,
				'colonia'             => $datos['colonia'] ?? null,
				'municipio'           => $datos['municipio'] ?? null,
				'entidad_federativa'  => $datos['entidad_federativa'] ?? null,
				'cp'                  => $datos['cp'] ?? null,
				'estado_civil'        => $datos['estado_civil'] ?? null,
				'como_se_entero' 	  => $datos['como_se_entero'] ?? '',
				'solicitud_id'        => $solicitudId,
			];
		}

		// 2. Crear solicitante
		$solicitante = Solicitante::create($datosPersona);

		// 3. Correos
		if (!empty($datos['correos']) && is_array($datos['correos'])) {
			foreach ($datos['correos'] as $correo) {
				if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
					Correo::create([
						'solicitante_id' => $solicitante->id,
						'email' => $correo,
					]);
				}
			}
		}

		// 4. Teléfonos
		if (!empty($datos['telefonos']) && is_array($datos['telefonos'])) {
			foreach ($datos['telefonos'] as $telefono) {
				if (!empty($telefono)) {
					Telefono::create([
						'solicitante_id' => $solicitante->id,
						'numero' => $telefono,
						'tipo' => null,
					]);
				}
			}
		}

		// 5. Representante
		if (!empty($datos['representante']) && $datos['representante'] != 0) {
			$representante = $datos['representante'];

			Representante::create([
				'solicitante_id'     => $solicitante->id,
				'nombre'             => $datos['nombre_representante'] ?? '',
				'apellido_paterno'   => $datos['apellido_p_representante'] ?? '',
				'apellido_materno'   => $datos['apellido_m_representante'] ?? null,
			]);
		}

		

		$this->guardarDocumentoIndividual($datos['identificacion'][0] ?? null, 'identificacion', $solicitante->id);
		$this->guardarDocumentoIndividual($datos['formato_privacidad'][0] ?? null, 'formato de privacidad', $solicitante->id);

		//Representante
		$this->guardarDocumentoIndividual($datos['acta_notarial'][0] ?? null, 'acta notarial', $solicitante->id);
		$this->guardarDocumentoIndividual($datos['acta_de_nacimiento'][0] ?? null, 'acta de nacimiento', $solicitante->id);
		$this->guardarDocumentoIndividual($datos['resolucion_judicial'][0] ?? null, 'resolucion judicial', $solicitante->id);


		// 7. Documentos generales de la solicitud
		$documentosListos = $this->guardarArchivos();

		foreach ($documentosListos as $doc) {
			DocumentoSolicitud::create([
				'solicitud_id'    => $solicitudId,
				'tipo'            => $doc['documento'],
				'nombre_original' => $doc['nombre_original'],
				'ruta'            => $doc['ruta'],
				'extension'       => $doc['extension'],
				'size'            => $doc['size'],
			]);
		}
	}

	protected function guardarDocumentoIndividual($archivo, $tipo, $solicitanteId, $guardarDB = true)
	{
		if ($archivo && !empty($archivo['path']) && file_exists($archivo['path'])) {
			$nombreOriginal = $archivo['name'];
			$nuevoNombre = uniqid() . '_' . $nombreOriginal;
			$destino = 'documentos';

			$rutaFinal = Storage::disk('public')->putFileAs(
				$destino,
				new HttpFile($archivo['path']),
				$nuevoNombre
			);

			$rutaPublica = 'storage/' . $rutaFinal;

			if($guardarDB){
				Documento::create([
					'solicitante_id'    => $solicitanteId,
					'tipo'              => $tipo,
					'nombre_original'   => $nombreOriginal,
					'ruta'              => $rutaPublica,
					'extension'         => $archivo['extension'] ?? pathinfo($nombreOriginal, PATHINFO_EXTENSION),
					'size'              => $archivo['size'] ?? null,
				]);
			}
			
			return $rutaPublica;
		}
		return null;
	}

	private function generarFolio(string $prefijo, string $clave, int $folio): string
	{
		$anio = now()->year;
		$folioFormateado = str_pad($folio, 4, '0', STR_PAD_LEFT);

		return ($this->materia === 'presencial')
			? "$prefijo-$clave-$folioFormateado-$anio"
			: "V-$prefijo-$clave-$folioFormateado-$anio";
	}

	public function save () {
		return Redirect::route('pre-mediacion.index')
			->success('Convenio registrado correctamente !'); 
	}
}
