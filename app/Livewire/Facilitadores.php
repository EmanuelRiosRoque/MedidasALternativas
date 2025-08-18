<?php

namespace App\Livewire;

use App\Models\Correo;
use Livewire\Component;
use App\Models\Telefono;
use App\Models\Facilitador;
use App\Models\SepomexColonia;
use App\Models\CorreoFacilitador;
use App\Models\TelefonoFacilitador;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;

class Facilitadores extends Component
{
    public int $tab = 1;
    //Datos generales
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

    //Datos adicionales
    public $duracion_encargo;
    public $numero_renovaciones;
    public $area_adscrito;
    public $autoridad_certificacion = '';
    public $especificacion_autoridad;
    public $clave_autoridad;

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

    public $infracciones;
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



    public function updatedCpSolicitante()
    {
        $this->colonias = SepomexColonia::where('codigo_postal', $this->cp_solicitante)
            ->get();

        if ($this->colonias->isNotEmpty()) {
            $this->entidad_federativa_solicitante = $this->colonias->first()->estado;
            $this->municipio_solicitante = $this->colonias->first()->municipio;
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
                'tipo' => $this->correo_etiqueta_temp ?? 'Sin etiqueta',
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
                'tipo' => $this->telefono_etiqueta_temp ?? 'Sin etiqueta',
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
        $periodo = trim($this->numero_renovaciones);

        if ($periodo !== '') {
            $this->periodos[] = $periodo;
            $this->numero_renovaciones = '';
        }
    }

    public function eliminarPeriodo($index)
    {
        unset($this->periodos[$index]);
        $this->periodos = array_values($this->periodos); // reindexar
    }

    public function cambiarTab($numero)
    {
        $this->tab = $numero;
    }

    public function save()
    {
        if (!empty($this->fotografia) && is_array($this->fotografia) && isset($this->fotografia[0])) {
            $rutaPublica = $this->guardarFotografiaFacilitador($this->fotografia[0]);
        }

        if (!empty($this->avale_autorizado) && isset($this->avale_autorizado[0])) {
            $rutaAvalAutorizado = $this->guardarDocumentoFacilitador($this->avale_autorizado[0]);
        }

        if (!empty($this->avale_especializacion) && isset($this->avale_especializacion[0])) {
            $rutaAvalEspecializacion = $this->guardarDocumentoFacilitador($this->avale_especializacion[0]);
        }
        
        if (!empty($this->avale_autorizado_arbitra) && isset($this->avale_autorizado_arbitra[0])) {
            $rutaAvalArbitra = $this->guardarDocumentoFacilitador($this->avale_autorizado_arbitra[0]);
        }

        if (!empty($this->avale_resolucion) && isset($this->avale_resolucion[0])) {
            $rutaAvalResolucion = $this->guardarDocumentoFacilitador($this->avale_resolucion[0]);
        }

        if (!empty($this->avale_materiales) && isset($this->avale_materiales[0])) {
            $rutaAvalMateriales = $this->guardarDocumentoFacilitador($this->avale_materiales[0]);
        }

        if (!empty($this->video_supervision) && isset($this->video_supervision[0])) {
            $rutaVideo = $this->guardarDocumentoFacilitador($this->video_supervision[0]);
        }

        if (!empty($this->publicacion_documento) && isset($this->publicacion_documento[0])) {
            $rutaDocuemento = $this->guardarDocumentoFacilitador($this->publicacion_documento[0]);
        }

        if (!empty($this->avale_dictamen) && isset($this->avale_dictamen[0])) {
            $rutaAvalDictamen = $this->guardarDocumentoFacilitador($this->avale_dictamen[0]);
        }

         if (!empty($this->avale_jucio) && isset($this->avale_jucio[0])) {
            $rutaAvalJuicio = $this->guardarDocumentoFacilitador($this->avale_jucio[0]);
        }

        $facilitador = Facilitador::create([
            'tipo' => $this->tipo,
            'nombre' => $this->nombre,
            'materia' => $this->materia,
            'estudios' => $this->estudios,
            'cedula' => $this->cedula,

            'clave_certificacion' => $this->clave_certificacion,
            'folio' => $this->folio,
            'clave_unica' => $this->clave_unica,
            'fecha_certificacion' => $this->fecha_certificacion,
            'vigencia_certificacion' => $this->vigencia_certificacion,
            'tipo_domicilio' => $this->tipo_domicilio_solicitante,
            'calle' => $this->calle_solicitante,
            'cp_solicitante' => $this->cp_solicitante,
            'colonia' => $this->colonia,
            'entidad_federativa_solicitante' => $this->entidad_federativa_solicitante,
            'municipio_solicitante' => $this->municipio_solicitante,
            'fotografia' => $rutaPublica ?? null, // Guarda la ruta

            'duracion_encargo' => $this->duracion_encargo,
            'area_adscrito' => $this->area_adscrito,
            'numero_renovaciones' => json_encode($this->periodos, JSON_UNESCAPED_UNICODE),
            'autoridad_certificacion' => $this->autoridad_certificacion,
            'especificacion_autoridad' => $this->especificacion_autoridad,
            'clave_autoridad' => $this->clave_autoridad,
            'autorizacion' => $this->autorizacion,
            'avale_autorizado' => $rutaAvalAutorizado ?? null,
            'especializacion' => $this->especializacion,
            'avale_especializacion' => $rutaAvalEspecializacion ?? null,
            'especializacion_arbitra' => $this->especializacion_arbitra,
            'avale_autorizado_arbitra' => $rutaAvalArbitra ?? null,
            'convenios_suscritos'=> $this->convenios_suscritos,
            'convenios_ejecutados'=> $this->convenios_ejecutados,
            'procedimientos_quejas'=> $this->procedimientos_quejas,
            'tiene_resolucion'=> $this->tiene_resolucion,
            'avale_resolucion'=> $rutaAvalResolucion ?? null,
            'infracciones' => $this->infracciones,
            'descripcion_sancion' => $this->descripcion_sancion,
            'cancelacion' => $this->cancelacion,
            'elementos_materiales' => $this->elementos_materiales,
            'avale_materiales' => $rutaAvalMateriales ?? null,
            'visitas_supervision' => $this->visitas_supervision,
            'fecha_supervision' => $this->fecha_supervision,
            'video_supervision' => $rutaVideo ?? null,
            'fecha_publicacion'=> $this->fecha_publicacion,
            'publicacion_documento'=> $rutaDocuemento  ?? null,
            'juicio_amparo'=> $this->juicio_amparo,
            'avale_jucio'=> $rutaAvalJuicio ?? null,
            'dictamen_cja'=> $this->dictamen_cja,
            'avale_dictamen'=> $rutaAvalDictamen ?? null,
        ]);

        if (!empty($this->correos) && is_array($this->correos)) {
            foreach ($this->correos as $correo) {
                if (filter_var($correo['direccion'], FILTER_VALIDATE_EMAIL)) {
                    CorreoFacilitador::create([
                        'facilitador_id' => $facilitador->id,
                        'email'          => $correo['direccion'],
                        'tipo'           => $correo['tipo'],
                    ]);
                }
            }
        }

        // Guardar teléfonos en tabla relacionada
        if (!empty($this->telefonos) && is_array($this->telefonos)) {
            foreach ($this->telefonos as $tel) {
                TelefonoFacilitador::create([
                    'facilitador_id' => $facilitador->id,
                    'numero'         => $tel['numero'],
                    'tipo'           => $tel['tipo'],
                ]);
            }
        }


        // Resetear formulario si lo deseas
        $this->reset();
        return redirect()->route('facilitadores.list')
            ->with('success', '¡Facilitadore creado correctamente!');
    }

    protected function guardarDocumentoFacilitador($archivo)
    {
        if (!empty($archivo['path']) && file_exists($archivo['path'])) {
            $nombreOriginal = $archivo['name'];
            $nuevoNombre = uniqid() . '_' . $nombreOriginal;

            // Carpeta de destino
            $destino = 'documentos_facilitador';

            $rutaFinal = Storage::disk('public')->putFileAs(
                $destino,
                new HttpFile($archivo['path']),
                $nuevoNombre
            );

            return 'storage/' . $rutaFinal; // Ruta pública
        }
        return null;
    }

    protected function guardarFotografiaFacilitador($archivo)
    {
        if (!empty($archivo['path']) && file_exists($archivo['path'])) {
            $nombreOriginal = $archivo['name'];
            $nuevoNombre = uniqid() . '_' . $nombreOriginal;

            // Carpeta de destino
            $destino = 'fotografias_facilitador';

            $rutaFinal = Storage::disk('public')->putFileAs(
                $destino,
                new HttpFile($archivo['path']),
                $nuevoNombre
            );

            return 'storage/' . $rutaFinal; // Ruta pública
        }
        return null;
    }

    public function render()
    {
        return view('livewire.facilitadores');
    }
}
