<?php

namespace App\Traits\ConvenioTraits;

use Masmerise\Toaster\Toaster;

trait HandleCrudLogicoPersonas
{
    /** @var string[] */
    protected array $camposPersona = [
        'persona', 'representante', 'persona_invitado', 'materia',
        'nombre_solicitante', 'apellido_p_solicitante', 'apellido_m_solicitante',
        'sexo_solicitante', 'edad_solicitante', 'fecha_nacimiento_solicitante',
        'escolaridad_solicitante', 'ocupacion_solicitante', 'nacionalidad_solicitante',
        'tipo_domicilio_solicitante', 'calle_solicitante', 'colonia', 'colonias',
        'municipio_solicitante', 'entidad_federativa_solicitante', 'correo_solicitante',
        'identificacion', 'acta_notarial', 'acta_de_nacimiento', 'resolucion_judicial',
        'cp_solicitante', 'formato_privacidad', 'como_se_entero',

        // Campos para persona moral / familiar
        'razon_social_solicitante', 'rfc_solicitante', 'instrumento_solicitante',
        'fecha_instrumento_solicitante', 'telefono_solicitante',
        'domicilio_solicitante', 'estado_civil_solicitante', 'correos', 'telefonos',

        // Representante
        'nombre_representante', 'apellido_p_representante', 'apellido_m_representante', 'doc_representante',

        // Otros
        'acudiran_juntos',
    ];

    // Constantes para evitar "números mágicos"
    private const DOC_ACTA_NOTARIAL = 1;
    private const DOC_ACTA_NACIMIENTO = 2;
    private const DOC_RESOLUCION_JUDICIAL = 3;
    private const DOC_CREDITO_POLIZA = 4;

    /** Helpers de contexto */
    protected function esCivilOMercantil(): bool
    {
        return in_array($this->materia, ['civil', 'mercantil'], true);
    }

    protected function esFamiliar(): bool
    {
        return $this->materia === 'familiar';
    }

    protected function docRepresentante(): array
    {
        return (array) ($this->doc_representante ?? []);
    }

    /**
     * Valida datos antes de agregar (o editar) una persona según:
     * - $tipo: 'solicitante' | 'invitado'
     * - $this->persona: 'fisica' | 'moral' (cuando aplica)
     * - $this->materia: 'civil' | 'mercantil' | 'familiar'
     */
    protected function validarPersonaAntesDeAgregar(string $tipo): void
    {
        // 1) En civil/mercantil, si es solicitante: al menos seleccionar tipo de persona (física/moral)
        if ($tipo === 'solicitante' && $this->esCivilOMercantil()) {
            $this->validate(['persona' => 'required']);
        }

        // 2) Construimos reglas según combinaciones
        $rules = [];

        if ($tipo === 'solicitante') {
            $rules = array_merge($rules, $this->rulesSolicitanteBase());

            if ($this->esCivilOMercantil()) {
                if ($this->persona === 'fisica') {
                    $rules = array_merge($rules, $this->rulesSolicitanteFisicaCivilMercantil());
                } elseif ($this->persona === 'moral') {
                    $rules = array_merge($rules, $this->rulesSolicitanteMoralCivilMercantil());
                }
            }

            if ($this->esFamiliar()) {
                $rules = array_merge($rules, $this->rulesSolicitanteFamiliar());
            }

            if ((int) $this->representante === 1) {
                $rules = array_merge($rules, $this->rulesRepresentanteObligatorio());
                $rules = array_merge($rules, $this->rulesDocumentosRepresentanteCondicionales());
            }
        }

        if ($tipo === 'invitado') {
            if ($this->esFamiliar()) {
                $rules = array_merge($rules, $this->rulesInvitadoFamiliar());
            }

            if ($this->esCivilOMercantil()) {
                if ($this->persona === 'fisica') {
                    $rules = array_merge($rules, $this->rulesInvitadoFisicaCivilMercantil());
                } elseif ($this->persona === 'moral') {
                    $rules = array_merge($rules, $this->rulesInvitadoMoralCivilMercantil());
                }
            }
        }

        // 🔹 3) Reglas adicionales según modalidad
        if ($this->modalidad == 1) {
            // Teléfono obligatorio, correo opcional
            $rules['telefonos'] = 'required|array|min:1';
            // correo puede existir pero no se fuerza
        } elseif ($this->modalidad == 2) {
            // Correo obligatorio, teléfono opcional
            $rules['correos'] = 'required|array|min:1';
        }

        $this->validate($rules);
    }


    /** -------- BLOQUE: Generadores de reglas de validación -------- */

    /** Reglas base comunes para "solicitante" (independiente de materia/persona) */
    protected function rulesSolicitanteBase(): array
    {
        return [
            'representante' => 'required',
        ];
    }

    /** Solicitante física en civil/mercantil */
    protected function rulesSolicitanteFisicaCivilMercantil(): array
    {
        return [
            'nombre_solicitante'            => 'required|string|max:255',
            'apellido_p_solicitante'        => 'required|string|max:255',
            'apellido_m_solicitante'        => 'required|string|max:255',
            'sexo_solicitante'              => 'required',
            'edad_solicitante'              => 'required',
            'fecha_nacimiento_solicitante'  => 'required|date',
            'escolaridad_solicitante'       => 'required|string|max:255',
            'ocupacion_solicitante'         => 'required|string|max:255',
            'nacionalidad_solicitante'      => 'required|string|max:255',
            'telefonos'                     => 'required|array|min:1',
            'correos'                       => 'required|array|min:1',
            'tipo_domicilio_solicitante'    => 'required|string|max:255',
            'calle_solicitante'             => 'required|string|max:255',
            'cp_solicitante'                => 'required|string|max:10',
            'colonia'                       => 'required|string|max:255',
            'municipio_solicitante'         => 'required|string|max:255',
            'entidad_federativa_solicitante'=> 'required|string|max:255',
            'identificacion'                => 'required|array|min:1',
            'formato_privacidad'            => 'required|array|min:1',
            'como_se_entero'                => 'required',
            'representante'                 => 'required',
        ];
    }

    /** Solicitante moral en civil/mercantil */
    protected function rulesSolicitanteMoralCivilMercantil(): array
    {
        return [
            'razon_social_solicitante'      => 'required',
            'instrumento_solicitante'       => 'required',
            'fecha_instrumento_solicitante' => 'required',
            'telefonos'                     => 'required|array|min:1',
            'correos'                       => 'required|array|min:1',
            'tipo_domicilio_solicitante'    => 'required|string|max:255',
            'calle_solicitante'             => 'required|string|max:255',
            'cp_solicitante'                => 'required|string|max:10',
            'colonia'                       => 'required|string|max:255',
            'municipio_solicitante'         => 'required|string|max:255',
            'entidad_federativa_solicitante'=> 'required|string|max:255',
            'identificacion'                => 'required|array|min:1',
            'formato_privacidad'            => 'required|array|min:1',
            'representante'                 => 'required',
            'como_se_entero'                => 'required',
            
        ];
    }

    /** Solicitante en materia familiar */
    protected function rulesSolicitanteFamiliar(): array
    {
        return [
            'nombre_solicitante'            => 'required|string|max:255',
            'apellido_p_solicitante'        => 'required|string|max:255',
            'apellido_m_solicitante'        => 'required|string|max:255',
            'sexo_solicitante'              => 'required',
            'edad_solicitante'              => 'required',
            'escolaridad_solicitante'       => 'required|string|max:255',
            'ocupacion_solicitante'         => 'required|string|max:255',
            'estado_civil_solicitante'      => 'required',
            'telefonos'                     => 'required|array|min:1',
            'correos'                       => 'required|array|min:1',
            'tipo_domicilio_solicitante'    => 'required|string|max:255',
            'calle_solicitante'             => 'required|string|max:255',
            'cp_solicitante'                => 'required|string|max:10',
            'colonia'                       => 'required|string|max:255',
            'municipio_solicitante'         => 'required|string|max:255',
            'entidad_federativa_solicitante'=> 'required|string|max:255',
            'identificacion'                => 'required|array|min:1',
            'formato_privacidad'            => 'required|array|min:1',
            'representante'                 => 'required',
            'como_se_entero'                => 'required',
        ];
    }

    /** Invitado en materia familiar */
    protected function rulesInvitadoFamiliar(): array
    {
        return [
            'nombre_solicitante'            => 'required|string|max:255',
            'apellido_p_solicitante'        => 'required|string|max:255',
            'apellido_m_solicitante'        => 'required|string|max:255',
            // 'telefonos'                     => 'required|array|min:1',
            // 'correos'                       => 'required|array|min:1',
        ];
    }

    /** Invitado física en civil/mercantil */
    protected function rulesInvitadoFisicaCivilMercantil(): array
    {
        return [
            'nombre_solicitante'            => 'required|string|max:255',
            'apellido_p_solicitante'        => 'required|string|max:255',
            'apellido_m_solicitante'        => 'required|string|max:255',

            // 'telefonos'                     => 'required|array|min:1',
            // 'correos'                       => 'required|array|min:1',
        ];
    }

    /** Invitado moral en civil/mercantil */
    protected function rulesInvitadoMoralCivilMercantil(): array
    {
        return [
            'razon_social_solicitante'      => 'required',
            'como_se_entero'         => 'required',

            // 'telefonos'                     => 'required|array|min:1',
            // 'correos'                       => 'required|array|min:1',
        ];
    }

    /** Reglas cuando hay representante */
    protected function rulesRepresentanteObligatorio(): array
    {
        return [
            'nombre_representante'          => 'required|string|max:255',
            'apellido_p_representante'      => 'required|string|max:255',
            'apellido_m_representante'      => 'required|string|max:255',
            'doc_representante'             => 'required|array|min:1',
        ];
    }

    /** Reglas condicionales por documentos del representante seleccionados */
    protected function rulesDocumentosRepresentanteCondicionales(): array
    {
        $rules = [];
        $docs = $this->docRepresentante();

        if (in_array(self::DOC_ACTA_NOTARIAL, $docs, true)) {
            $rules['acta_notarial'] = 'required';
        }

        if (in_array(self::DOC_ACTA_NACIMIENTO, $docs, true)) {
            $rules['acta_de_nacimiento'] = 'required';
        }

        if (in_array(self::DOC_RESOLUCION_JUDICIAL, $docs, true)) {
            $rules['resolucion_judicial'] = 'required';
        }

        if (in_array(self::DOC_RESOLUCION_JUDICIAL, $docs, true)) {
            $rules['resolucion_judicial'] = 'required';
        }
        
        if (in_array(self::DOC_CREDITO_POLIZA, $docs, true)) {
            $rules['titulo_credito'] = 'required';
        }

        return $rules;
    }

    /** -------- BLOQUE: CRUD lógico en arrays -------- */

    public function seleccionarPersona($index, string $tipo = 'solicitante'): void
    {
        $array = $tipo === 'invitado' ? $this->invitadoArray : $this->solicitanteArray;

        if (!isset($array[$index])) {
            return;
        }

        $this->detalleSeleccionado = $array[$index];
        $this->detalleSeleccionado['index'] = $index;
        $this->detalleSeleccionado['tipo']  = $tipo;
        $this->mostrarModal = true;
    }

    public function agregarPersona(string $tipo = 'solicitante'): void
    {
        $this->validarPersonaAntesDeAgregar($tipo);

        $datos = $this->tomarDatosDeFormulario();

        if ($tipo === 'solicitante') {
            $this->solicitanteArray[] = $datos;
        } else {
            $this->invitadoArray[] = $datos;
        }

        Toaster::success('¡Participante agregado!');
        $this->limpiarCamposPersona(preservarPersona: false);
    }

    public function cargarEdicion(): void
    {
        if (empty($this->detalleSeleccionado)) {
            return;
        }

        $this->modoEdicion   = true;
        $this->indiceEdicion = $this->detalleSeleccionado['index'] ?? null;

        foreach ($this->camposPersona as $campo) {
            $key = $this->campoAIndice($campo);
            if (array_key_exists($key, $this->detalleSeleccionado)) {
                $this->$campo = $this->detalleSeleccionado[$key];
            }
        }

        $this->mostrarModal = false;
    }

    public function editarPersona(): void
    {
        if (!isset($this->detalleSeleccionado['index'])) {
            return;
        }

        $index = $this->detalleSeleccionado['index'];
        $tipo  = $this->detalleSeleccionado['tipo'] ?? 'solicitante';

        $nuevoRegistro = $this->tomarDatosDeFormulario();

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

    public function eliminarPersona($index, string $tipo = 'solicitante'): void
    {
        if ($tipo === 'invitado') {
            unset($this->invitadoArray[$index]);
            $this->invitadoArray = array_values($this->invitadoArray);
        } else {
            unset($this->solicitanteArray[$index]);
            $this->solicitanteArray = array_values($this->solicitanteArray);
        }

        Toaster::warning('¡Eliminado correctamente!');
    }

    /** -------- BLOQUE: Utilidades de formulario -------- */

    /**
     * Toma los datos del formulario a partir de $camposPersona, mapeando
     * *_solicitante -> clave sin sufijo para el array final.
     */
    protected function tomarDatosDeFormulario(): array
    {
        $datos = [];
        foreach ($this->camposPersona as $campo) {
            $key = $this->campoAIndice($campo);
            $datos[$key] = $campo === 'persona' ? $this->persona : $this->$campo;
        }
        return $datos;
    }

    protected function campoAIndice(string $campo): string
    {
        return str_replace('_solicitante', '', $campo);
    }

    /** -------- BLOQUE: Reseteo de campos -------- */

    public function limpiarCamposPersona(bool $preservarPersona = false): void
    {
        if (!$preservarPersona) {
            $this->reset('persona', 'persona_invitado');
        }

        $this->reset([
            'modoEdicion', 'representante',
            'nombre_solicitante', 'apellido_p_solicitante', 'apellido_m_solicitante',
            'sexo_solicitante', 'edad_solicitante', 'fecha_nacimiento_solicitante',
            'escolaridad_solicitante', 'ocupacion_solicitante', 'nacionalidad_solicitante',
            'tipo_domicilio_solicitante', 'calle_solicitante', 'domicilio_solicitante',
            'municipio_solicitante', 'entidad_federativa_solicitante',
            'correo_solicitante', 'estado_civil_solicitante',
            'razon_social_solicitante', 'rfc_solicitante',
            'instrumento_solicitante', 'fecha_instrumento_solicitante',
            'telefono_solicitante', 'cp_solicitante', 'colonia', 'colonias',
            'como_se_entero',

            // Documentos / arrays
            'identificacion', 'acta_notarial', 'acta_de_nacimiento', 'resolucion_judicial',
            'formato_privacidad', 'doc_representante', 'correos', 'telefonos',

            // Representante
            'nombre_representante', 'apellido_p_representante', 'apellido_m_representante',
        ]);
    }
}
