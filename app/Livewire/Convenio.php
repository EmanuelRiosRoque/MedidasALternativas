<?php

namespace App\Livewire;

use App\Traits\ConvenioTraits\HandleCrudLogicoPersonas;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class Convenio extends Component
{
	use WithFilePond;

	public int $tab = 1;
	// Input Radios
	public  $modalidad;
	public  $materia;
	public  $tipo_convenio;

	//Datos solicitante
	public array $solicitanteArray = [];
	public array $invitadoArray = [];

	public $persona;
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

	// Moral
	public string $razon_social_solicitante = '';
	public string $rfc_solicitante= '';
	public string $instrumento_solicitante = '';
	public string $fecha_instrumento_solicitante = '';
	public string $telefono_solicitante = '';

	// Documento
	public $identificacion;
	public $acta_notarial;

	public $detalleSeleccionado = [];
	public $mostrarModal = false;

	public bool $modoEdicion = false;
	public ?int $indiceEdicion = null;


	
	public function mount() {
		// $this->solicitanteArray = [
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'moral',
		// 		'representante' => '1',
		// 		'nombre' => '',
		// 		'sexo' => '',
		// 		'edad' => '',
		// 		'fecha_nacimiento' => '',
		// 		'escolaridad' => '',
		// 		'ocupacion' => '',
		// 		'nacionalidad' => '',
		// 		'tipo_domicilio' => 'Oficina',
		// 		'calle' => 'Av. Revolución 321',
		// 		'colonia' => 'Tacubaya',
		// 		'municipio' => 'Miguel Hidalgo',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'contacto@empresamoral.com',
		// 		'identificacion' => null,
		// 		'razon_social' => 'Grupo Moral S.A. de C.V.',
		// 		'rfc' => 'GMS850101ABC',
		// 		'instrumento' => 'Acta Constitutiva No. 1001',
		// 		'fecha_instrumento' => '2020-04-10',
		// 		'telefono' => '555-123-4567',
		// 	]
		// ];
		
		// $this->invitadoArray = [
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'fisica',
		// 		'representante' => '0',
		// 		'nombre' => 'Carlos Ramírez Díaz',
		// 		'sexo' => 'Masculino',
		// 		'edad' => '28',
		// 		'fecha_nacimiento' => '1996-01-10',
		// 		'escolaridad' => 'Universidad',
		// 		'ocupacion' => 'Ingeniero Civil',
		// 		'nacionalidad' => 'Mexicana',
		// 		'tipo_domicilio' => 'Casa',
		// 		'calle' => 'Calle Río Lerma 45',
		// 		'colonia' => 'Cuauhtémoc',
		// 		'municipio' => 'Cuauhtémoc',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'carlos.ramirez@example.com',
		// 		'identificacion' => null,
		// 		'razon_social' => '',
		// 		'rfc' => '',
		// 		'instrumento' => '',
		// 		'fecha_instrumento' => '',
		// 		'telefono' => '',
		// 	],
		// 	[
		// 		'persona' => 'moral',
		// 		'representante' => '1',
		// 		'nombre' => '',
		// 		'sexo' => '',
		// 		'edad' => '',
		// 		'fecha_nacimiento' => '',
		// 		'escolaridad' => '',
		// 		'ocupacion' => '',
		// 		'nacionalidad' => '',
		// 		'tipo_domicilio' => 'Oficina',
		// 		'calle' => 'Av. Revolución 321',
		// 		'colonia' => 'Tacubaya',
		// 		'municipio' => 'Miguel Hidalgo',
		// 		'entidad_federativa' => 'CDMX',
		// 		'correo' => 'contacto@empresamoral.com',
		// 		'identificacion' => null,
		// 		'razon_social' => 'Grupo Moral S.A. de C.V.',
		// 		'rfc' => 'GMS850101ABC',
		// 		'instrumento' => 'Acta Constitutiva No. 1001',
		// 		'fecha_instrumento' => '2020-04-10',
		// 		'telefono' => '555-123-4567',
		// 	]
		// ];
	}

	public function updatedPersona()
	{
		$this->limpiarCamposPersona(preservarPersona: true);
	}


	// Crud-logico para solicitante y invitados
	use HandleCrudLogicoPersonas;
	

	

	// Limpiar despues de cada accion
	public function limpiarCamposPersona(bool $preservarPersona = false)
	{
		if (!$preservarPersona) {
			$this->reset('persona');
		}

		$this->reset([
			'representante',
			'nombre_solicitante',
			'sexo_solicitante',
			'edad_solicitante',
			'fecha_nacimiento_solicitante',
			'escolaridad_solicitante',
			'ocupacion_solicitante',
			'nacionalidad_solicitante',
			'tipo_domicilio_solicitante',
			'calle_solicitante',
			'colonia_solicitante',
			'municipio_solicitante',
			'entidad_federativa_solicitante',
			'correo_solicitante',
			'razon_social_solicitante',
			'rfc_solicitante',
			'instrumento_solicitante',
			'fecha_instrumento_solicitante',
			'telefono_solicitante',
			'identificacion',
			'acta_notarial',
		]);

		$this->dispatch('filepond-reset-identificacion');
		$this->dispatch('filepond-reset-acta_notarial');
	}




	public function render()
	{
		return view('livewire.convenio');
	}
}
