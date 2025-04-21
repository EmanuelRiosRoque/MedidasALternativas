<?php

namespace App\Traits\ConvenioTraits;

use Masmerise\Toaster\Toaster;

trait HandleCrudLogicoPersonas
{
    /**
     * Campos que se usan para personas (solicitante/invitado).
     * Se puede agregar/quitar campos fácilmente aquí.
     */
    protected array $camposPersona = [
        'persona', 'representante', 'persona_invitado',
        'nombre_solicitante', 'sexo_solicitante', 'edad_solicitante', 'fecha_nacimiento_solicitante',
        'escolaridad_solicitante', 'ocupacion_solicitante', 'nacionalidad_solicitante',
        'tipo_domicilio_solicitante', 'calle_solicitante', 'colonia_solicitante',
        'municipio_solicitante', 'entidad_federativa_solicitante', 'correo_solicitante',
        'identificacion', 'acta_notarial', 'acta_de_nacimiento', 'resolucion_judicial','cp_solicitante',

        // Campos adicionales para moral o familiar
        'razon_social_solicitante', 'rfc_solicitante', 'instrumento_solicitante',
        'fecha_instrumento_solicitante', 'telefono_solicitante',
        'domicilio_solicitante', 'estado_civil_solicitante',
    ];

    public function seleccionarPersona($index, string $tipo = 'solicitante')
    {
        $array = $tipo === 'invitado' ? $this->invitadoArray : $this->solicitanteArray;

        if (!isset($array[$index])) return;

        $this->detalleSeleccionado = $array[$index];
        $this->detalleSeleccionado['index'] = $index;
        $this->detalleSeleccionado['tipo'] = $tipo;
        $this->mostrarModal = true;
    }


    public function agregarPersona(string $tipo = 'solicitante')
    {
        $datos = [];

        foreach ($this->camposPersona as $campo) {
            $key = str_replace('_solicitante', '', $campo);
            $datos[$key] = $this->$campo;
        }

        if ($tipo === 'solicitante') {
            $this->solicitanteArray[] = $datos;
        } else {
            $this->invitadoArray[] = $datos;
        }
        Toaster::success('Participante agregado !');

        $this->limpiarCamposPersona(preservarPersona: false);
    }

    // public function agregarPersona($payload)
    // {
    //     $tipo = $payload['tipo']; // 'solicitante' o 'invitado'
    //     $datos = $payload['datos'];

    //     if ($tipo === 'solicitante') {
    //         $this->solicitanteArray[] = $datos;
    //     } else {
    //         $this->invitadoArray[] = $datos;
    //     }

    //      Toaster::success('Participante agregado !');
    // }

    public function cargarEdicion()
    {
        if (empty($this->detalleSeleccionado)) return;

        $this->modoEdicion = true;
        $this->indiceEdicion = $this->detalleSeleccionado['index'];

        foreach ($this->camposPersona as $campo) {
            $key = str_replace('_solicitante', '', $campo);
            if (array_key_exists($key, $this->detalleSeleccionado)) {
                $this->$campo = $this->detalleSeleccionado[$key];
            }
        }

        $this->mostrarModal = false;
    }

    public function editarPersona()
    {
        if (!isset($this->detalleSeleccionado['index'])) return;

        $index = $this->detalleSeleccionado['index'];
        $tipo = $this->detalleSeleccionado['tipo'] ?? 'solicitante';

        $nuevoRegistro = [];

        foreach ($this->camposPersona as $campo) {
            $key = str_replace('_solicitante', '', $campo);
            $nuevoRegistro[$key] = $this->$campo;
        }

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

    public function eliminarPersona($index, string $tipo = 'solicitante')
    {
        if ($tipo === 'invitado') {
            unset($this->invitadoArray[$index]);
            $this->invitadoArray = array_values($this->invitadoArray);
        } else {
            unset($this->solicitanteArray[$index]);
            $this->solicitanteArray = array_values($this->solicitanteArray);
        }
        Toaster::warning('Eliminado correctamente!');

    }

    // Limpiar despues de cada accion
	public function limpiarCamposPersona(bool $preservarPersona = false)
	{
		if (!$preservarPersona) {
			$this->reset('persona');
            $this->reset('persona_invitado');
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
			'domicilio_solicitante',
			'estado_civil_solicitante',
            'cp_solicitante'
		]);

		$this->dispatch('filepond-reset-identificacion');
		$this->dispatch('filepond-reset-acta_notarial');
	}

    
}
