<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facilitador;
use App\Models\SepomexColonia;
use App\Models\CorreoFacilitador;

use Illuminate\Support\Facades\DB;
use App\Models\TelefonoFacilitador;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Catalogos\CatPoderesJudiciales;

class Facilitadores extends Component
{
    public int $tab = 1;

    // Datos generales
    public $tipo = '';
    public $nombre;
    public $clave_certificacion;
    public $folio;
    public $clave_unica;
    public $fecha_certificacion;
    public $vigencia_certificacion;

    public $correo_temp = '';
    public $correo_etiqueta_temp = '';

    public $telefono_temp = '';
    public $telefono_etiqueta_temp = '';

    public $correos = [];
    public $telefonos = [];
    public $periodos = [];
    public $tipo_domicilio_solicitante = '';
    public $calle_solicitante;
    public $fotografia;

    // Datos adicionales
    public $duracion_encargo = '5 años en el encargo';
    public $numero_renovaciones;
    public $area_adscrito;
    public $autoridad_certificacion = '';
    public $especificacion_autoridad;
    public $clave_autoridad = '';

    public $autorizacion = '';
    public $avale_autorizado;

    public $especializacion = '';
    public $avale_especializacion;

    public $especializacion_arbitra = '';
    public $avale_autorizado_arbitra;

    public $convenios_suscritos;
    public $convenios_ejecutados;
    public $procedimientos_quejas;

    public $tiene_resolucion = '';
    public $avale_resolucion;

    public $infracciones = [];
    public $descripcion_sancion;
    public $cancelacion;

    public $elementos_materiales;
    public $avale_materiales;
    public $quejas_recibidas = '';
    public $visitas_supervision = '';
    public $fecha_supervision;
    public $video_supervision;
    public $juicio_amparo;
    public $fecha_publicacion;
    public $publicacion_documento;
    public $dictamen_cja = '';
    public $avale_dictamen;
    public $avale_jucio;
    public $cedula;
    public $estudios;
    public $materia;

    public $cp_solicitante = '';
    /** @var \Illuminate\Support\Collection|\App\Models\SepomexColonia[] */
    public $colonias = [];
    public $colonia = '';
    public $entidad_federativa_solicitante = '';
    public $municipio_solicitante = '';

    public $poderesJudiciales = '';
    public array $pjLookup = []; // ['Poder Judicial de ...' => '09', ...]

    public function mount(): void
    {
        $this->poderesJudiciales = CatPoderesJudiciales::where('activo', true)
            ->orderBy('entidad')
            ->get(['id', 'cve_ent', 'entidad', 'poder_judicial'])
            ->toArray(); // <-- importante

        $this->pjLookup = collect($this->poderesJudiciales)
            ->pluck('cve_ent', 'poder_judicial')
            ->all();
    }

    public function updatedAutoridadCertificacion($value): void
    {
        $this->clave_autoridad = $this->pjLookup[$value] ?? '';
    }

    public function updatedCpSolicitante()
    {
        $this->colonias = SepomexColonia::select('id', 'asentamiento', 'estado', 'municipio', 'codigo_postal')
            ->where('codigo_postal', $this->cp_solicitante)
            ->orderBy('asentamiento')
            ->get();

        if ($this->colonias->isNotEmpty()) {
            $first = $this->colonias->first();
            $this->entidad_federativa_solicitante = $first->estado;
            $this->municipio_solicitante = $first->municipio;
        } else {
            $this->entidad_federativa_solicitante = '';
            $this->municipio_solicitante = '';
        }

        $this->colonia = '';
    }

    public function agregarCorreo()
    {
        if ($this->correo_temp) {
            $this->correos[] = [
                'direccion' => $this->correo_temp,
                'tipo'      => $this->correo_etiqueta_temp ?: 'Sin etiqueta',
            ];
            $this->correo_temp = '';
            $this->correo_etiqueta_temp = '';
        }
    }

    public function eliminarCorreo($index)
    {
        unset($this->correos[$index]);
        $this->correos = array_values($this->correos);
    }

    public function agregarTelefono()
    {
        if ($this->telefono_temp) {
            $this->telefonos[] = [
                'numero' => $this->telefono_temp,
                'tipo'   => $this->telefono_etiqueta_temp ?: 'Sin etiqueta',
            ];
            $this->telefono_temp = '';
            $this->telefono_etiqueta_temp = '';
        }
    }

    public function eliminarTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos);
    }

    public function agregarPeriodo()
    {
        $periodo = trim((string) $this->numero_renovaciones);
        if ($periodo !== '') {
            $this->periodos[] = $periodo;
            $this->numero_renovaciones = '';
        }
    }

    public function eliminarPeriodo($index)
    {
        unset($this->periodos[$index]);
        $this->periodos = array_values($this->periodos);
    }

    public function cambiarTab($numero)
    {
        $this->tab = (int) $numero;
    }

    public function save()
    {
        // Mapeo propiedad => carpeta destino
        $uploadMap = [
            'fotografia'               => 'fotografias_facilitador',
            'avale_autorizado'         => 'documentos_facilitador',
            'avale_especializacion'    => 'documentos_facilitador',
            'avale_autorizado_arbitra' => 'documentos_facilitador',
            'avale_resolucion'         => 'documentos_facilitador',
            'avale_materiales'         => 'documentos_facilitador',
            'video_supervision'        => 'documentos_facilitador',
            'publicacion_documento'    => 'documentos_facilitador',
            'avale_dictamen'           => 'documentos_facilitador',
            'avale_jucio'              => 'documentos_facilitador',
        ];

        // Guardar archivos opcionales (primer archivo de cada input)
        $paths = [];
        foreach ($uploadMap as $prop => $dir) {
            $paths[$prop] = $this->saveFirstUploadFrom($prop, $dir);
        }

        DB::transaction(function () use ($paths) {
            // Crear facilitador
            $facilitador = Facilitador::create([
                'tipo'                            => $this->tipo,
                'nombre'                          => $this->nombre,
                'materia'                         => $this->materia,
                'estudios'                        => $this->estudios,
                'cedula'                          => $this->cedula,
                'clave_certificacion'             => $this->clave_certificacion,
                'folio'                           => $this->folio,
                'clave_unica'                     => $this->clave_unica,
                'fecha_certificacion'             => $this->fecha_certificacion,
                'vigencia_certificacion'          => $this->vigencia_certificacion,
                'tipo_domicilio'                  => $this->tipo_domicilio_solicitante,
                'calle'                           => $this->calle_solicitante,
                'cp_solicitante'                  => $this->cp_solicitante,
                'colonia'                         => $this->colonia,
                'entidad_federativa_solicitante'  => $this->entidad_federativa_solicitante,
                'municipio_solicitante'           => $this->municipio_solicitante,
                'fotografia'                      => $paths['fotografia'] ?? null,

                'duracion_encargo'                => $this->duracion_encargo,
                'area_adscrito'                   => $this->area_adscrito,
                'numero_renovaciones'             => $this->numero_renovaciones,
                'autoridad_certificacion'         => $this->autoridad_certificacion,
                'especificacion_autoridad'        => $this->especificacion_autoridad,
                'clave_autoridad'                 => $this->clave_autoridad,
                'autorizacion'                    => $this->autorizacion,

                'avale_autorizado'                => $paths['avale_autorizado'] ?? null,
                'especializacion'                 => $this->especializacion,
                'avale_especializacion'           => $paths['avale_especializacion'] ?? null,
                'especializacion_arbitra'         => $this->especializacion_arbitra,
                'avale_autorizado_arbitra'        => $paths['avale_autorizado_arbitra'] ?? null,

                'convenios_suscritos'             => $this->convenios_suscritos,
                'convenios_ejecutados'            => $this->convenios_ejecutados,
                'procedimientos_quejas'           => $this->procedimientos_quejas,
                'tiene_resolucion'                => $this->tiene_resolucion,
                'avale_resolucion'                => $paths['avale_resolucion'] ?? null,

                'infracciones'                    => $this->infracciones,
                'descripcion_sancion'             => $this->descripcion_sancion,
                'cancelacion'                     => $this->cancelacion,
                'elementos_materiales'            => $this->elementos_materiales,
                'avale_materiales'                => $paths['avale_materiales'] ?? null,

                'visitas_supervision'             => $this->visitas_supervision,
                'fecha_supervision'               => $this->fecha_supervision,
                'video_supervision'               => $paths['video_supervision'] ?? null,
                'fecha_publicacion'               => $this->fecha_publicacion,
                'publicacion_documento'           => $paths['publicacion_documento'] ?? null,
                'juicio_amparo'                   => $this->juicio_amparo,
                'avale_jucio'                     => $paths['avale_jucio'] ?? null,
                'dictamen_cja'                    => $this->dictamen_cja,
                'avale_dictamen'                  => $paths['avale_dictamen'] ?? null,
            ]);

            // Correos válidos (insert en bloque)
            if (!empty($this->correos) && is_array($this->correos)) {
                $now = now();
                $emails = [];
                foreach ($this->correos as $c) {
                    $direccion = $c['direccion'] ?? null;
                    if ($direccion && filter_var($direccion, FILTER_VALIDATE_EMAIL)) {
                        $emails[] = [
                            'facilitador_id' => $facilitador->id,
                            'email'          => $direccion,
                            'tipo'           => $c['tipo'] ?? 'Sin etiqueta',
                            'created_at'     => $now,
                            'updated_at'     => $now,
                        ];
                    }
                }
                if ($emails) {
                    CorreoFacilitador::insert($emails);
                }
            }

            // Teléfonos (insert en bloque)
            if (!empty($this->telefonos) && is_array($this->telefonos)) {
                $now = now();
                $phones = [];
                foreach ($this->telefonos as $t) {
                    $numero = $t['numero'] ?? null;
                    if ($numero) {
                        $phones[] = [
                            'facilitador_id' => $facilitador->id,
                            'numero'         => $numero,
                            'tipo'           => $t['tipo'] ?? 'Sin etiqueta',
                            'created_at'     => $now,
                            'updated_at'     => $now,
                        ];
                    }
                }
                if ($phones) {
                    TelefonoFacilitador::insert($phones);
                }
            }
        });

        // Mantén la pestaña actual y limpia el resto
        $this->resetExcept('tab');

        return redirect()
            ->route('facilitadores.list')
            ->with('success', '¡Facilitador creado correctamente!');
    }

    /**
     * Guarda el primer archivo del arreglo de una propiedad (si existe).
     */
    private function saveFirstUploadFrom(string $prop, string $dir): ?string
    {
        $first = $this->{$prop}[0] ?? null;
        if (!$first) {
            return null;
        }
        return $this->storeUploadedFile($first, $dir);
    }

    /**
     * Almacena un archivo subido (estructura FilePond/Dropzone-like: ['path', 'name']).
     */
    private function storeUploadedFile(array $archivo, string $destino): ?string
    {
        $path = $archivo['path'] ?? null;
        if (!$path || !is_file($path)) {
            return null;
        }

        $nombreOriginal = $archivo['name'] ?? basename($path);
        $nuevoNombre    = uniqid('', true) . '_' . $nombreOriginal;

        $rutaFinal = Storage::disk('public')->putFileAs(
            $destino,
            new HttpFile($path),
            $nuevoNombre
        );

        return $rutaFinal ? ('storage/' . $rutaFinal) : null;
    }

    public function render()
    {
        return view('livewire.facilitadores');
    }
}
