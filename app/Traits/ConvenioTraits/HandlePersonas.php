<?php

namespace App\Traits\ConvenioTraits;

use App\Models\{Solicitante, Correo, Telefono, Representante};
use Illuminate\Support\Arr;

trait HandlePersonas
{
    /**
     * Punto único para crear/adjuntar una persona (solicitante o invitado)
     */
    protected function guardarPersonaRelacionada(int $solicitudId, array $datos, string $rol = 'solicitante'): Solicitante
    {
        $payload = $this->buildPersonaData($solicitudId, $datos, $rol);
        $solicitante = Solicitante::create($payload);

        $this->guardarContactos($solicitante->id, $datos);
        $this->guardarRepresentanteSiAplica($solicitante->id, $datos);
        $this->guardarDocsIndividualesPersona($solicitante->id, $datos);

        return $solicitante;
    }

    /**
     * Normaliza el payload según materia y rol
     */
    protected function buildPersonaData(int $solicitudId, array $datos, string $rol): array
    {
        $base = [
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
            'como_se_entero'      => $datos['como_se_entero'] ?? '',
            'nacionalidad'       => $datos['nacionalidad'] ?? null,
            'solicitud_id'        => $solicitudId,
        ];

        if (in_array($this->materia, ['mercantil', 'civil'])) {
            $civil = [
                'persona'            => $datos['persona'] ?? 'fisica',
                'rfc'                => $datos['rfc'] ?? null,
                'razon_social'       => $datos['razon_social'] ?? null,
                'instrumento'        => $datos['instrumento'] ?? null,
                'fecha_nacimiento'   => !empty($datos['fecha_nacimiento'])  ? $datos['fecha_nacimiento']  : null,
                'fecha_instrumento'  => !empty($datos['fecha_instrumento']) ? $datos['fecha_instrumento'] : null,
            ];
            return array_merge($base, $civil);
        }

        // familiar
        $familiar = [
            'persona'      => 'familiar',
            'estado_civil' => $datos['estado_civil'] ?? null,
        ];
        return array_merge($base, $familiar);
    }

    /**
     * Correos y teléfonos
     */
    protected function guardarContactos(int $solicitanteId, array $datos): void
    {
        foreach (Arr::wrap($datos['correos'] ?? []) as $correo) {
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                Correo::create(['solicitante_id' => $solicitanteId, 'email' => $correo]);
            }
        }
        foreach (Arr::wrap($datos['telefonos'] ?? []) as $tel) {
            if (!empty($tel)) {
                Telefono::create(['solicitante_id' => $solicitanteId, 'numero' => $tel, 'tipo' => null]);
            }
        }
    }

    /**
     * Representante (si aplica)
     */
    protected function guardarRepresentanteSiAplica(int $solicitanteId, array $datos): void
    {
        if (empty($datos['representante']) || (int)$datos['representante'] === 0) {
            return;
        }
        Representante::create([
            'solicitante_id'   => $solicitanteId,
            'nombre'           => $datos['nombre_representante'] ?? '',
            'apellido_paterno' => $datos['apellido_p_representante'] ?? '',
            'apellido_materno' => $datos['apellido_m_representante'] ?? null,
        ]);
    }

    /**
     * Documentos individuales por persona
     */
    protected function guardarDocsIndividualesPersona(int $solicitanteId, array $datos): void
    {
        // Map clave_entrada => tipo_doc
        $map = [
            'identificacion'      => 'identificacion',
            'formato_privacidad'  => 'formato de privacidad',
            'acta_notarial'       => 'acta notarial',
            'acta_de_nacimiento'  => 'acta de nacimiento',
            'resolucion_judicial' => 'resolucion judicial',
            'titulo_credito'      => 'titulo o credito',
        ];

        foreach ($map as $campo => $tipo) {
            $this->guardarDocumentoIndividual($datos[$campo][0] ?? null, $tipo, $solicitanteId);
        }
    }
}
