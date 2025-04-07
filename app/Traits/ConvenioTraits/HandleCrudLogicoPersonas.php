<?php

namespace App\Traits\ConvenioTraits;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HandleCrudLogicoPersonas
{
	/**
     * Selecciona una persona del array correspondiente (solicitante o invitado),
     * guarda sus datos en detalleSeleccionado y muestra el modal para edición.
     *
     * @param int $index
     * @param string $tipo 'solicitante' o 'invitado'
    */
    public function seleccionarPersona($index,string $tipo = 'solicitante')
	{
		$array = $tipo === 'invitado' ? $this->invitadoArray : $this->solicitanteArray;

		if (!isset($array[$index])) return;

		$this->detalleSeleccionado = $array[$index];
		$this->detalleSeleccionado['index'] = $index;
		$this->detalleSeleccionado['tipo'] = $tipo;
		$this->mostrarModal = true;
	}

 	/**
     * Agrega una persona al array correspondiente (solicitante o invitado),
     * utilizando los datos actuales del formulario. Luego limpia los campos.
     *
     * @param string $tipo 'solicitante' o 'invitado'
    */
	// public function agregarPersona(string $tipo = 'solicitante')
	// {
	// 	$datos = [
	// 		'persona' => $this->persona,
	// 		'representante' => $this->representante,
	// 		'nombre' => $this->nombre_solicitante,
	// 		'sexo' => $this->sexo_solicitante,
	// 		'edad' => $this->edad_solicitante,
	// 		'fecha_nacimiento' => $this->fecha_nacimiento_solicitante,
	// 		'escolaridad' => $this->escolaridad_solicitante,
	// 		'ocupacion' => $this->ocupacion_solicitante,
	// 		'nacionalidad' => $this->nacionalidad_solicitante,
	// 		'tipo_domicilio' => $this->tipo_domicilio_solicitante,
	// 		'calle' => $this->calle_solicitante,
	// 		'colonia' => $this->colonia_solicitante,
	// 		'municipio' => $this->municipio_solicitante,
	// 		'entidad_federativa' => $this->entidad_federativa_solicitante,
	// 		'correo' => $this->correo_solicitante,
	// 		'identificacion' => $this->identificacion,

	// 		// Moral
	// 		'razon_social' => $this->razon_social_solicitante,
	// 		'rfc' => $this->rfc_solicitante,
	// 		'instrumento' => $this->instrumento_solicitante,
	// 		'fecha_instrumento' => $this->fecha_instrumento_solicitante,
	// 		'telefono' => $this->telefono_solicitante,

	// 		// Familiar
	// 		'domicilio' => $this->domicilio_solicitante,
	// 		'estado_civil' => $this->estado_civil_solicitante
	// 	];

	// 	// dd($datos);

	// 	if ($tipo === 'solicitante') {
	// 		$this->solicitanteArray[] = $datos;
	// 	} elseif ($tipo === 'invitado') {
	// 		$this->invitadoArray[] = $datos;
	// 	}

	// 	$this->limpiarCamposPersona(preservarPersona: false);
	// }

	public function agregarPersona(string $tipo = 'solicitante')
	{
		// Guarda archivo si existe
		$datos = [
			'persona' => $this->persona,
			'representante' => $this->representante,
			'nombre' => $this->nombre_solicitante,
			'sexo' => $this->sexo_solicitante,
			'edad' => $this->edad_solicitante,
			'fecha_nacimiento' => $this->fecha_nacimiento_solicitante,
			'escolaridad' => $this->escolaridad_solicitante,
			'ocupacion' => $this->ocupacion_solicitante,
			'nacionalidad' => $this->nacionalidad_solicitante,
			'tipo_domicilio' => $this->tipo_domicilio_solicitante,
			'calle' => $this->calle_solicitante,
			'colonia' => $this->colonia_solicitante,
			'municipio' => $this->municipio_solicitante,
			'entidad_federativa' => $this->entidad_federativa_solicitante,
			'correo' => $this->correo_solicitante,
			'identificacion' => $this->identificacion,

			// Moral
			'razon_social' => $this->razon_social_solicitante,
			'rfc' => $this->rfc_solicitante,
			'instrumento' => $this->instrumento_solicitante,
			'fecha_instrumento' => $this->fecha_instrumento_solicitante,
			'telefono' => $this->telefono_solicitante,

			// Familiar
			'domicilio' => $this->domicilio_solicitante,
			'estado_civil' => $this->estado_civil_solicitante
		];

		if ($tipo === 'solicitante') {
			$this->solicitanteArray[] = $datos;
		} elseif ($tipo === 'invitado') {
			$this->invitadoArray[] = $datos;
		}

		$this->limpiarCamposPersona(preservarPersona: false);
	}

 	/**
     * Carga los datos de la persona seleccionada en los campos del formulario
     * para poder ser editada. También cambia el estado a modoEdicion.
    */
	public function cargarEdicion()
	{
		if (empty($this->detalleSeleccionado)) return;

		$this->modoEdicion = true;
		$this->indiceEdicion = $this->detalleSeleccionado['index'];

		$this->persona = $this->detalleSeleccionado['persona'];
		$this->representante = $this->detalleSeleccionado['representante'];
		$this->nombre_solicitante = $this->detalleSeleccionado['nombre'];
		$this->sexo_solicitante = $this->detalleSeleccionado['sexo'];
		$this->edad_solicitante = $this->detalleSeleccionado['edad'];
		$this->fecha_nacimiento_solicitante = $this->detalleSeleccionado['fecha_nacimiento'];
		$this->escolaridad_solicitante = $this->detalleSeleccionado['escolaridad'];
		$this->ocupacion_solicitante = $this->detalleSeleccionado['ocupacion'];
		$this->nacionalidad_solicitante = $this->detalleSeleccionado['nacionalidad'];
		$this->tipo_domicilio_solicitante = $this->detalleSeleccionado['tipo_domicilio'];
		$this->calle_solicitante = $this->detalleSeleccionado['calle'];
		$this->colonia_solicitante = $this->detalleSeleccionado['colonia'];
		$this->municipio_solicitante = $this->detalleSeleccionado['municipio'];
		$this->entidad_federativa_solicitante = $this->detalleSeleccionado['entidad_federativa'];
		$this->correo_solicitante = $this->detalleSeleccionado['correo'];
		$this->identificacion = $this->detalleSeleccionado['identificacion'];

		// Si es moral
		$this->razon_social_solicitante = $this->detalleSeleccionado['razon_social'];
		$this->rfc_solicitante = $this->detalleSeleccionado['rfc'];
		$this->instrumento_solicitante = $this->detalleSeleccionado['instrumento'];
		$this->fecha_instrumento_solicitante = $this->detalleSeleccionado['fecha_instrumento'];
		$this->telefono_solicitante = $this->detalleSeleccionado['telefono'];

		// Si es familiar
		$this->domicilio_solicitante = $this->detalleSeleccionado['domicilio'];
		$this->estado_civil_solicitante = $this->detalleSeleccionado['estado_civil'];
		
		// Opcional: cerrar el modal
		$this->mostrarModal = false;
	}

	/**
     * Reemplaza los datos de una persona previamente seleccionada (en modo edición)
     * con los valores actuales del formulario.
    */
	public function editarPersona()
	{
		if (!is_null($this->detalleSeleccionado['index'] ?? null)) {
			$index = $this->detalleSeleccionado['index'];
			$tipo = $this->detalleSeleccionado['tipo'] ?? 'solicitante';

			$nuevoRegistro = [
				'persona' => $this->persona,
				'representante' => $this->representante,
				'nombre' => $this->nombre_solicitante,
				'sexo' => $this->sexo_solicitante,
				'edad' => $this->edad_solicitante,
				'fecha_nacimiento' => $this->fecha_nacimiento_solicitante,
				'escolaridad' => $this->escolaridad_solicitante,
				'ocupacion' => $this->ocupacion_solicitante,
				'nacionalidad' => $this->nacionalidad_solicitante,
				'tipo_domicilio' => $this->tipo_domicilio_solicitante,
				'calle' => $this->calle_solicitante,
				'colonia' => $this->colonia_solicitante,
				'municipio' => $this->municipio_solicitante,
				'entidad_federativa' => $this->entidad_federativa_solicitante,
				'correo' => $this->correo_solicitante,
				'identificacion' => $this->identificacion,
				'razon_social' => $this->razon_social_solicitante,
				'rfc' => $this->rfc_solicitante,
				'instrumento' => $this->instrumento_solicitante,
				'fecha_instrumento' => $this->fecha_instrumento_solicitante,
				'telefono' => $this->telefono_solicitante,
				'domicilio' => $this->domicilio_solicitante,
				'estado_civil' => $this->estado_civil_solicitante,
			];


			if ($tipo === 'invitado') {
				$this->invitadoArray[$index] = $nuevoRegistro;
			} else {
				$this->solicitanteArray[$index] = $nuevoRegistro;
			}

			$this->limpiarCamposPersona();
			$this->detalleSeleccionado = [];
			$this->mostrarModal = false;
			$this->modoEdicion = false;

		}
	}

	/**
     * Elimina una persona del array correspondiente por índice.
     *
     * @param int $index
     * @param string $tipo 'solicitante' o 'invitado'
    */
	public function eliminarPersona($index,string $tipo = 'solicitante')
	{
		if ($tipo === 'invitado') {
			unset($this->invitadoArray[$index]);
			$this->invitadoArray = array_values($this->invitadoArray);
		} else {
			unset($this->solicitanteArray[$index]);
			$this->solicitanteArray = array_values($this->solicitanteArray);
		}
	}
}
