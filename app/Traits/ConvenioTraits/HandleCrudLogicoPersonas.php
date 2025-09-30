<?php

namespace App\Traits\ConvenioTraits;

use Masmerise\Toaster\Toaster;
use Illuminate\Validation\ValidationException;

trait HandleCrudLogicoPersonas
{
    /** @var string[] */
    protected array $camposPersona = [
        'persona',
        'representante',
        'persona_invitado',
        'materia',
        'nombre_solicitante',
        'apellido_p_solicitante',
        'apellido_m_solicitante',
        'sexo_solicitante',
        'edad_solicitante',
        'fecha_nacimiento_solicitante',
        'escolaridad_solicitante',
        'ocupacion_solicitante',
        'nacionalidad_solicitante',
        'tipo_domicilio_solicitante',
        'calle_solicitante',
        'colonia',
        'colonias',
        'municipio_solicitante',
        'entidad_federativa_solicitante',
        'correo_solicitante',
        'identificacion',
        'acta_notarial',
        'acta_de_nacimiento',
        'resolucion_judicial',
        'titulo_credito', // ⟵ NUEVO: para “Título de crédito y póliza”
        'cp_solicitante',
        'formato_privacidad',
        'como_se_entero',

        // Campos para persona moral / familiar
        'razon_social_solicitante',
        'rfc_solicitante',
        'instrumento_solicitante',
        'fecha_instrumento_solicitante',
        'telefono_solicitante',
        'domicilio_solicitante',
        'estado_civil_solicitante',
        'correos',
        'telefonos',

        // Representante
        'nombre_representante',
        'apellido_p_representante',
        'apellido_m_representante',
        'doc_representante',

        // Otros
        'acudiran_juntos',
    ];

    // Constantes para evitar "números mágicos"
    private const DOC_ACTA_NOTARIAL    = 1;
    private const DOC_ACTA_NACIMIENTO  = 2;
    private const DOC_RESOLUCION_JUD   = 3;
    private const DOC_CREDITO_POLIZA   = 4;

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
            $this->validate(['persona' => 'required'], ['persona.required' => 'Selecciona si es persona física o moral.']);
        }

        $rules    = [];
        $messages = [];

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
                if ($this->esCivilOMercantil()) {
                    // En civil/mercantil: nombres del representante y selección de documentos son OBLIGATORIOS
                    $rules = array_merge($rules, $this->rulesRepresentanteObligatorio());
                } else {
                    // En familiar: la selección de documentos es opcional
                    $rules['doc_representante'] = 'nullable|array';
                }

                // ✅ SIEMPRE exigir archivos si seleccionaron documentos, sin importar la materia
                $docRule = $this->rulesDocumentosRepresentanteCondicionales();
                $rules   = array_merge($rules, $docRule);

                // Mensajes para cada documento (si fue seleccionado)
                if (isset($docRule['acta_notarial'])) {
                    $messages['acta_notarial.required'] = 'Seleccionaste "Instrumento notarial", sube el archivo.';
                    $messages['acta_notarial.array']    = 'El archivo de "Instrumento notarial" es inválido.';
                    $messages['acta_notarial.min']      = 'Adjunta al menos un archivo de "Instrumento notarial".';
                }
                if (isset($docRule['acta_de_nacimiento'])) {
                    $messages['acta_de_nacimiento.required'] = 'Seleccionaste "Acta de registro civil", sube el archivo.';
                    $messages['acta_de_nacimiento.array']    = 'El archivo de "Acta de registro civil" es inválido.';
                    $messages['acta_de_nacimiento.min']      = 'Adjunta al menos un archivo del "Acta de registro civil".';
                }
                if (isset($docRule['resolucion_judicial'])) {
                    $messages['resolucion_judicial.required'] = 'Seleccionaste "Resolución judicial", sube el archivo.';
                    $messages['resolucion_judicial.array']    = 'El archivo de "Resolución judicial" es inválido.';
                    $messages['resolucion_judicial.min']      = 'Adjunta al menos un archivo de "Resolución judicial".';
                }
                if (isset($docRule['titulo_credito'])) {
                    $messages['titulo_credito.required'] = 'Seleccionaste "Título de crédito y póliza", sube el archivo.';
                    $messages['titulo_credito.array']    = 'El archivo de "Título de crédito y póliza" es inválido.';
                    $messages['titulo_credito.min']      = 'Adjunta al menos un archivo de "Título de crédito y póliza".';
                }

                // (Opcional) asegurar valores válidos en el checkbox
                $rules['doc_representante.*'] = 'in:1,2,3,4';
                $messages['doc_representante.*.in'] = 'Documento seleccionado inválido.';
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

        // 3) Reglas adicionales según modalidad
        if ($this->modalidad == 1) {
            $rules['telefonos'] = 'required|array|min:1';
            $messages['telefonos.required'] = 'Captura al menos un teléfono.';
        } elseif ($this->modalidad == 2) {
            $rules['correos'] = 'required|array|min:1';
            $messages['correos.required'] = 'Captura al menos un correo electrónico.';
        }

        $this->validate($rules, $messages);
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
            'entidad_federativa_solicitante' => 'required|string|max:255',
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
            'entidad_federativa_solicitante' => 'required|string|max:255',
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
            'entidad_federativa_solicitante' => 'required|string|max:255',
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
        ];
    }

    /** Invitado física en civil/mercantil */
    protected function rulesInvitadoFisicaCivilMercantil(): array
    {
        return [
            'nombre_solicitante'            => 'required|string|max:255',
            'apellido_p_solicitante'        => 'required|string|max:255',
            'apellido_m_solicitante'        => 'required|string|max:255',
        ];
    }

    /** Invitado moral en civil/mercantil */
    protected function rulesInvitadoMoralCivilMercantil(): array
    {
        return [
            'razon_social_solicitante'      => 'required',
            'como_se_entero'                => 'required',
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
        // normaliza a enteros por si vienen como strings
        $docs = array_map('intval', $this->docRepresentante());

        if (in_array(self::DOC_ACTA_NOTARIAL, $docs)) {
            $rules['acta_notarial'] = 'required|array|min:1';
        }

        if (in_array(self::DOC_ACTA_NACIMIENTO, $docs)) {
            $rules['acta_de_nacimiento'] = 'required|array|min:1';
        }

        if (in_array(self::DOC_RESOLUCION_JUD, $docs)) {
            $rules['resolucion_judicial'] = 'required|array|min:1';
        }

        if (in_array(self::DOC_CREDITO_POLIZA, $docs)) {
            // ⟵ IMPORTANTE: coincide con tu dropzone: wire:model="titulo_credito"
            $rules['titulo_credito'] = 'required|array|min:1';
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
        try {
            $this->validarPersonaAntesDeAgregar($tipo);
        } catch (ValidationException $e) {
            // Muestra el primer mensaje en Toaster
            $msg = collect($e->validator->errors()->all())->first() ?? 'Validación inválida';
            Toaster::error($msg);
            return;
        }

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
            'modoEdicion',
            'representante',
            'nombre_solicitante',
            'apellido_p_solicitante',
            'apellido_m_solicitante',
            'sexo_solicitante',
            'edad_solicitante',
            'fecha_nacimiento_solicitante',
            'escolaridad_solicitante',
            'ocupacion_solicitante',
            'nacionalidad_solicitante',
            'tipo_domicilio_solicitante',
            'calle_solicitante',
            'domicilio_solicitante',
            'municipio_solicitante',
            'entidad_federativa_solicitante',
            'correo_solicitante',
            'estado_civil_solicitante',
            'razon_social_solicitante',
            'rfc_solicitante',
            'instrumento_solicitante',
            'fecha_instrumento_solicitante',
            'telefono_solicitante',
            'cp_solicitante',
            'colonia',
            'colonias',
            'como_se_entero',

            // Documentos / arrays
            'identificacion',
            'acta_notarial',
            'acta_de_nacimiento',
            'resolucion_judicial',
            'titulo_credito', // ⟵ NUEVO
            'formato_privacidad',
            'doc_representante',
            'correos',
            'telefonos',

            // Representante
            'nombre_representante',
            'apellido_p_representante',
            'apellido_m_representante',
        ]);
    }
}
