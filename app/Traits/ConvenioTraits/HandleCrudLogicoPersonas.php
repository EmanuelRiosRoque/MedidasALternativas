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
        'persona', 'representante', 'persona_invitado', 'materia',
        'nombre_solicitante','apellido_p_solicitante','apellido_m_solicitante',  'sexo_solicitante', 'edad_solicitante', 'fecha_nacimiento_solicitante',
        'escolaridad_solicitante', 'ocupacion_solicitante', 'nacionalidad_solicitante',
        'tipo_domicilio_solicitante', 'calle_solicitante', 'colonia',
        'municipio_solicitante', 'entidad_federativa_solicitante', 'correo_solicitante',
        'identificacion', 'acta_notarial', 'acta_de_nacimiento', 'resolucion_judicial','cp_solicitante','formato_privacidad','como_se_entero',
        // Campos adicionales para moral o familiar
        'razon_social_solicitante', 'rfc_solicitante', 'instrumento_solicitante',
        'fecha_instrumento_solicitante', 'telefono_solicitante',
        'domicilio_solicitante', 'estado_civil_solicitante','correos', 'telefonos',
        'nombre_representante',
        'apellido_p_representante',
        'apellido_m_representante',
    ];

    protected function validarPersonaAntesDeAgregar(string $tipo)
    {
        // Solo validamos si es solicitante, materia civil y persona física

        if (
            $tipo === 'solicitante' &&
            ($this->materia === 'civil' || $this->materia === 'mercantil')
        ) {
            $this->validate([
                'persona' => 'required'
            ]);
        }

        if (
            $tipo === 'solicitante' &&
            $this->persona === 'fisica' &&
            ($this->materia === 'civil' || $this->materia === 'mercantil')
        ) {
            $rules = [
                'nombre_solicitante' => 'required|string|max:255',
                'apellido_p_solicitante' => 'required|string|max:255',
                'apellido_m_solicitante' => 'required|string|max:255',
                'sexo_solicitante' => 'required',
                'edad_solicitante' => 'required',
                'fecha_nacimiento_solicitante' => 'required|date',
                'escolaridad_solicitante' => 'required|string|max:255',
                'ocupacion_solicitante' => 'required|string|max:255',
                'nacionalidad_solicitante' => 'required|string|max:255',
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
                'tipo_domicilio_solicitante' => 'required|string|max:255',
                'calle_solicitante' => 'required|string|max:255',
                'cp_solicitante' => 'required|string|max:10',
                'colonia' => 'required|string|max:255',
                'municipio_solicitante' => 'required|string|max:255',
                'entidad_federativa_solicitante' => 'required|string|max:255',
                'identificacion' => 'required|array|min:1',
                'formato_privacidad' => 'required|array|min:1',
                'representante' => 'required',
            ];

            if ($this->representante == 1) {
                // Datos del representante
                $rules['nombre_representante'] = 'required|string|max:255';
                $rules['apellido_p_representante'] = 'required|string|max:255';
                $rules['apellido_m_representante'] = 'required|string|max:255';

                // Documentos seleccionados
                $rules['doc_representante'] = 'required|array|min:1';

                if (in_array(1, (array) $this->doc_representante)) {
                    $rules['acta_notarial'] = 'required';
                }

                if (in_array(2, (array) $this->doc_representante)) {
                    $rules['acta_de_nacimiento'] = 'required';
                }

                if (in_array(3, (array) $this->doc_representante)) {
                    $rules['resolucion_judicial'] = 'required';
                }
            }

            $this->validate($rules);
        }



        if (
            $tipo === 'solicitante' &&
            $this->persona === 'moral' &&
            ($this->materia === 'civil' || $this->materia === 'mercantil')
        ) {
            $rules = [
                'razon_social_solicitante' => 'required',
                'instrumento_solicitante' => 'required',
                'fecha_instrumento_solicitante' => 'required',
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
                'tipo_domicilio_solicitante' => 'required|string|max:255',
                'calle_solicitante' => 'required|string|max:255',
                'cp_solicitante' => 'required|string|max:10',
                'colonia' => 'required|string|max:255',
                'municipio_solicitante' => 'required|string|max:255',
                'entidad_federativa_solicitante' => 'required|string|max:255',
                'identificacion' => 'required|array|min:1',
                'formato_privacidad' => 'required|array|min:1',
                'representante' => 'required',
            ];

             if ($this->representante == 1) {
                // Datos del representante
                $rules['nombre_representante'] = 'required|string|max:255';
                $rules['apellido_p_representante'] = 'required|string|max:255';
                $rules['apellido_m_representante'] = 'required|string|max:255';

                // Documentos seleccionados
                $rules['doc_representante'] = 'required|array|min:1';

                if (in_array(1, (array) $this->doc_representante)) {
                    $rules['acta_notarial'] = 'required';
                }

                if (in_array(2, (array) $this->doc_representante)) {
                    $rules['acta_de_nacimiento'] = 'required';
                }

                if (in_array(3, (array) $this->doc_representante)) {
                    $rules['resolucion_judicial'] = 'required';
                }
            }

            $this->validate($rules);
        }

        if ($tipo === 'solicitante' && $this->materia === 'familiar') {
            $this->validate([
                'nombre_solicitante' => 'required|string|max:255',
                'apellido_p_solicitante' => 'required|string|max:255',
                'apellido_m_solicitante' => 'required|string|max:255',
                'sexo_solicitante' => 'required',
                'edad_solicitante' => 'required',
                'escolaridad_solicitante' => 'required|string|max:255',
                'ocupacion_solicitante' => 'required|string|max:255',
                'estado_civil_solicitante' => 'required',
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
                'tipo_domicilio_solicitante' => 'required|string|max:255',
                'calle_solicitante' => 'required|string|max:255',
                'cp_solicitante' => 'required|string|max:10',
                'colonia' => 'required|string|max:255',
                'municipio_solicitante' => 'required|string|max:255',
                'entidad_federativa_solicitante' => 'required|string|max:255',
                'identificacion' => 'required|array|min:1',
                'formato_privacidad' => 'required|array|min:1',
                'representante' => 'required'
            ]);
        }


        if ($tipo === 'invitado' && $this->materia === 'familiar') {
            $this->validate([
                'nombre_solicitante' => 'required|string|max:255',
                'apellido_p_solicitante' => 'required|string|max:255',
                'apellido_m_solicitante' => 'required|string|max:255',
               
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
            ]);
        }


       if (
            $tipo === 'invitado' &&
            $this->persona === 'fisica' &&
            ($this->materia === 'civil' || $this->materia === 'mercantil')
        ) {
            $this->validate([
                'nombre_solicitante' => 'required|string|max:255',
                'apellido_p_solicitante' => 'required|string|max:255',
                'apellido_m_solicitante' => 'required|string|max:255',
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
            ]);
        }


        if (
            $tipo === 'invitado' &&
            $this->persona === 'moral' &&
            ($this->materia === 'civil' || $this->materia === 'mercantil')
        ) {
            $this->validate([
                'razon_social_solicitante' => 'required',
                'telefonos' => 'required|array|min:1',
                'correos' => 'required|array|min:1',
            ]);
        }
        
    }


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
        $this->validarPersonaAntesDeAgregar($tipo);

        $datos = [];

        foreach ($this->camposPersona as $campo) {
            $key = str_replace('_solicitante', '', $campo);

            if ($key === 'persona') {
                $datos[$key] = $this->persona;
            } else {
                $datos[$key] = $this->$campo;
            }
        }

        if ($tipo === 'solicitante') {
            $this->solicitanteArray[] = $datos;
        } else {
            $this->invitadoArray[] = $datos;
        }

        Toaster::success('Participante agregado !');
        $this->limpiarCamposPersona(preservarPersona: $tipo === 'invitado');
    }





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
            'modoEdicion',
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
            'cp_solicitante',
            'como_se_entero',
            'formato_privacidad',
            'apellido_p_solicitante',
            'apellido_m_solicitante',
            'colonia',
            'acta_de_nacimiento',
            'resolucion_judicial',
            'nombre_representante',
            'apellido_p_representante',
            'apellido_m_representante',
            'doc_representante',
            'correos',
            'telefonos'
		]);

		$this->dispatch('filepond-reset-identificacion');
		$this->dispatch('filepond-reset-acta_notarial');
	}

    
}
