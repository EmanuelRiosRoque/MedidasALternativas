<?php

namespace App\Livewire\Solicitud;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agenda;
use App\Models\Correo;
use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Invitacion;
use Masmerise\Toaster\Toaster;
use App\Mail\InvitacionMediacion;
use App\Models\DocumentoSolicitud;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class InvitacionesPanel extends Component
{
    // Props de entrada
    public ?Agenda $evento = null;           // Evento de Pre-mediación (normalmente el que ya enviabas)
    public ?Agenda $eventoMediacion = null;  // NUEVO: Evento de Mediación (tipo_proceso_id=2) para precargar en esa etapa
    public int $solicitudId;
    public string $modalidad;

    // Modelo cargado
    public ?Solicitud $solicitud = null;

    // Form (1ª o N-ésima)
    public ?string $enlaceReunion = null;     // 1ª en línea
    public ?string $urlNuevaInv   = null;     // N-ésima en línea
    public ?string $fechaAtencion = '';       // 1ª en línea (lectura)
    public ?string $fechaNuevaInv = '';       // N-ésima en línea / presencial (N-ésima)
    public ?string $fechaEnvio    = '';       // 1ª presencial

    // Horarios
    public ?string $horaInicio = '';
    public ?string $horaFin = '';
    public ?string $horaInicioInvitado = '';
    public ?string $horaFinInvitado = '';

    /** Selects de hora */
    public array $horarios = [];

    /** Listado */
    public Collection $invitaciones;

    /** Correos */
    public Collection $correosSolicitantes;
    public Collection $correosInvitados;

    /** UI: pregunta de resultado (siempre visible cuando aplica) */
    public bool $mostrarPreguntaAceptacion = false;
    public ?int $aceptoProceso = null;        // Pre: aceptó mediación ; Mediación: hubo acuerdo
    public ?int $motivoCancelacion = null;    // Solo Pre-mediación cuando NO acepta

    /** Tipo de proceso actual */
    public ?int $tipoProcesoId = null;

    /** Config: máximos por etapa */
    protected int $maxInvPre = 2;   // Pre-mediación
    protected int $maxInvMed = 10;  // Mediación

    /** Estatus (solo 5) */
    public array $estatusLabels = [ 5 => 'Activo' ];
    public $manifestaciones = null;
    
    protected $rules = [
        'enlaceReunion' => ['nullable', 'url'],
        'urlNuevaInv'   => ['nullable', 'url'],
        'fechaAtencion' => ['nullable', 'date'],
        'fechaNuevaInv' => ['nullable', 'date'],
        'fechaEnvio'    => ['nullable', 'date'],
    ];

    public function mount(?Agenda $evento = null, int $solicitudId, string $modalidad): void
    {
        $this->evento       = $evento;
        $this->solicitudId  = $solicitudId;
        $this->modalidad    = $modalidad;

        $this->solicitud           = Solicitud::find($this->solicitudId);
        $this->correosSolicitantes = collect();
        $this->correosInvitados    = collect();
        $this->horarios            = $this->generarHorarios('09:00', '19:00');

        $this->cargarTipoProceso();
        $this->cargarCorreos();
        $this->cargarInvitaciones();

        // NUEVO: localizar el evento de Mediación (para precargas en esa etapa)
        $this->eventoMediacion = Agenda::query()
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 2) // Mediación
            ->orderBy('fecha')             // el primero cronológicamente; ajusta si ocupas otro criterio
            ->first();

        $this->sincronizarEstadoUI();

        // Calcular siguiente consecutivo en la etapa
        $nextEtapa = (int) ($this->invitaciones->max('numero_inv') ?? 0) + 1;
        $esPre     = (int)($this->tipoProcesoId ?? 0) === 1;
        $esMed     = (int)($this->tipoProcesoId ?? 0) === 2;

        $esPrimeraPre = $esPre && $nextEtapa === 1;
        $esPrimeraMed = $esMed && $nextEtapa === 1;

        // Precargas:
        // - En Pre-mediación: desde $this->evento (como ya lo tenías)
        // - En Mediación:     desde $this->eventoMediacion (no del primer evento)
        if ($esPrimeraPre && $this->evento) {
            $this->precargarDesdeEvento($this->evento, true);
        } elseif ($esPrimeraMed && $this->eventoMediacion) {
            $this->precargarDesdeEvento($this->eventoMediacion, false);
        } else {
            // Importante: limpiar para evitar precargas no deseadas (ej: 2ª de Pre)
            $this->horaInicio = $this->horaFin = $this->horaInicioInvitado = $this->horaFinInvitado = '';
        }
    }

    /** Helper: evento activo según etapa */
    private function eventoActual(): ?Agenda
    {
        return ((int)($this->tipoProcesoId ?? 0) === 2)
            ? $this->eventoMediacion
            : $this->evento;
    }

    /** Precargar campos desde un evento Agenda */
    private function precargarDesdeEvento(Agenda $ev, bool $tambienFechas = true): void
    {
        $this->horaInicio         = $this->trimHi($ev->hora_inicio);
        $this->horaFin            = $this->trimHi($ev->hora_fin);
        $this->horaInicioInvitado = $this->trimHi($ev->hora_inicio_invitado);
        $this->horaFinInvitado    = $this->trimHi($ev->hora_fin_invitado);

        if ($tambienFechas) {
            // En tu flujo original, esto se usaba de “lectura” o para presencial 1ª
            $this->fechaAtencion = $ev->fecha ?? null;
            $this->fechaEnvio    = $ev->fecha ?? null;
        }
    }

    private function cargarTipoProceso(): void
    {
        $this->tipoProcesoId = Solicitud::whereKey($this->solicitudId)->value('tipo_proceso_id');
        if ($this->tipoProcesoId !== null) $this->tipoProcesoId = (int) $this->tipoProcesoId;
    }

    private function trimHi(?string $t): ?string { return $t ? substr($t, 0, 5) : null; }

    private function generarHorarios(string $inicio, string $fin): array
    {
        $out = [];
        $h  = Carbon::createFromFormat('H:i', $inicio);
        $hf = Carbon::createFromFormat('H:i', $fin);
        while ($h < $hf) {
            $out[$h->format('H:i')] = $h->format('g:i A');
            $h->addMinutes(30);
        }
        return $out;
    }

    /** Invitaciones SOLO de la etapa actual */
    private function cargarInvitaciones(): void
    {
        $this->cargarTipoProceso();

        $this->invitaciones = Invitacion::where('solicitud_id', $this->solicitudId)
            ->when($this->tipoProcesoId !== null, fn($q) => $q->where('tipo_proceso_id', $this->tipoProcesoId))
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->get();
    }

    /** Última invitación SOLO de la etapa actual */
    private function ultimaInvitacion(): ?Invitacion
    {
        $this->cargarTipoProceso();

        return Invitacion::where('solicitud_id', $this->solicitudId)
            ->when($this->tipoProcesoId !== null, fn($q) => $q->where('tipo_proceso_id', $this->tipoProcesoId))
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->first();
    }

    private function cargarCorreos(): void
    {
        $rows = Correo::query()
            ->select('correos.email', 'solicitantes.tipo_solicitante')
            ->join('solicitantes', 'correos.solicitante_id', '=', 'solicitantes.id')
            ->where('solicitantes.solicitud_id', $this->solicitudId)
            ->whereNotNull('solicitantes.facilitador_id')
            ->whereNotNull('solicitantes.estatus_id')
            ->get();

        $this->correosSolicitantes = $rows->where('tipo_solicitante', 'solicitante')->pluck('email')->values();
        $this->correosInvitados    = $rows->where('tipo_solicitante', 'invitado')->pluck('email')->values();
    }

    private function sincronizarEstadoUI(): void
    {
        $ultima = $this->ultimaInvitacion();

        $this->mostrarPreguntaAceptacion = (bool)($ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso));

        if (!$this->mostrarPreguntaAceptacion) {
            $this->aceptoProceso = null;
            $this->motivoCancelacion = null;
        }
    }

    private function formatoHorario(?string $inicio, ?string $fin): string
    {
        if (!$inicio || !$fin) return '—';
        return Carbon::parse($inicio)->format('H:i') . ' - ' . Carbon::parse($fin)->format('H:i');
    }

    public function store(string $context = 'primera'): void
    {

        $this->cargarTipoProceso();
        $this->solicitud = Solicitud::find($this->solicitudId);
        if (!$this->solicitud) { Toaster::error('No se encontró la solicitud.'); return; }

        // Si no hay etapa, arrancamos en Pre-mediación (1)
        if ($this->tipoProcesoId === null) {
            $this->tipoProcesoId = 1;
            $this->solicitud->update(['tipo_proceso_id' => 1]);
            $this->solicitud->refresh();
        }

        $ultima = $this->ultimaInvitacion();

        // 1) Si existe última sin asistencia registrada -> bloquear (todas las etapas)
        if ($ultima && is_null($ultima->asistio)) {
            Toaster::warning("Registra la asistencia de la {$this->etiquetaSing()} #{$ultima->numero_inv} antes de crear una nueva.");
            return;
        }

        // 2) Consecutivo a crear — por etapa
        $next = (int)(Invitacion::where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', $this->tipoProcesoId)
            ->max('numero_inv') ?? 0) + 1;

        // 3) Límite por tipo de proceso
        if ((int)$this->tipoProcesoId === 1 && $next > $this->maxInvPre) {
            Toaster::warning("En Pre-mediación solo se permiten {$this->maxInvPre} invitaciones.");
            return;
        }
        if ((int)$this->tipoProcesoId === 2 && $next > $this->maxInvMed) {
            Toaster::warning("En Mediación solo se permiten {$this->maxInvMed} sesiones.");
            return;
        }

        // 4) Reglas para 2ª y subsecuentes SOLO en Pre-mediación
        if ((int)$this->tipoProcesoId === 1 && $next >= 2 && $ultima) {
            if ($ultima->asistio === 1) {
                if (is_null($ultima->acepta_proceso)) {
                    Toaster::warning('Confirma si aceptaron mediación antes de crear otra invitación.');
                    return;
                }
                if ((int)$ultima->acepta_proceso === 1) {
                    Toaster::warning('Ya aceptaron mediación. No se permiten nuevas invitaciones en esta etapa.');
                    return;
                }
            }
        }

        // 4b) En Mediación: si ya hubo acuerdo, bloquear
        if ((int)$this->tipoProcesoId === 2 && $ultima && (int)$ultima->acepta_proceso === 1) {
            Toaster::warning('Ya hubo convenio/acuerdo. No se permiten nuevas sesiones.');
            return;
        }

        // 5) Validación dinámica por modalidad/contexto
        $rules = [];
        $isPre = ((int)$this->tipoProcesoId === 1);
        $ev    = $this->eventoActual();
        $opcionSeparados = (int)($ev->opcion_invitacion ?? 1) === 0; // 0=separados, 1=juntos

        if ($this->modalidad === 'linea') {
            if ($context === 'nueva') {
                // N-ésima en línea: URL + fecha + horarios
                $rules['urlNuevaInv']   = ['required', 'url'];
                $rules['fechaNuevaInv'] = ['required', 'date'];
                $rules['horaInicio']    = ['required'];
                $rules['horaFin']       = ['required'];
                if ($opcionSeparados) {
                    $rules['horaInicioInvitado'] = ['required'];
                    $rules['horaFinInvitado']    = ['required'];
                }
            } else {
                // Primera en línea:
                //  - Pre-mediación: SOLO URL (callout informativo)
                //  - Mediación: URL + horarios (pueden venir precargados desde $eventoMediacion)
                $rules['enlaceReunion'] = ['required', 'url'];

                if (!$isPre) {
                    $rules['horaInicio'] = ['required'];
                    $rules['horaFin']    = ['required'];
                    if ($opcionSeparados) {
                        $rules['horaInicioInvitado'] = ['required'];
                        $rules['horaFinInvitado']    = ['required'];
                    }
                }
            }
        } else {
            // Presencial: pedir horarios también
            if ($context === 'nueva') {
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['fechaEnvio']    = ['required', 'date'];
            }
            $rules['horaInicio'] = ['required'];
            $rules['horaFin']    = ['required'];
        }

        $this->validate($rules);

        // Inputs según modalidad/contexto
        $url = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->urlNuevaInv : $this->enlaceReunion)
            : null;

        $fechaAtencion = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : ($ev->fecha ?? null))
            : null;

        $fechaEnvio = $this->modalidad !== 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : $this->fechaEnvio)
            : null;

        // FK segura
        $facilitadorId = $this->solicitud->facilitador_id;
        if ($facilitadorId && !User::whereKey($facilitadorId)->exists()) {
            $facilitadorId = null;
        }

        // Horarios armados (si en pre/primera no se capturan, tomamos del evento si existen)
        $horaInicio          = $this->horaInicio ?: $this->trimHi($ev->hora_inicio ?? null);
        $horaFin             = $this->horaFin    ?: $this->trimHi($ev->hora_fin ?? null);
        $esSeparados         = ($this->modalidad === 'linea') && $opcionSeparados;
        $horaInicioInvitado  = $esSeparados ? ($this->horaInicioInvitado ?: $this->trimHi($ev->hora_inicio_invitado ?? null)) : null;
        $horaFinInvitado     = $esSeparados ? ($this->horaFinInvitado    ?: $this->trimHi($ev->hora_fin_invitado    ?? null)) : null;

        // Crear invitación/sesión
        $inv = Invitacion::create([
            'modalidad'            => $this->modalidad,
            'solicitud_id'         => $this->solicitudId,
            'facilitador_id'       => $facilitadorId,
            'url'                  => $url,
            'fecha_envio'          => $fechaEnvio,    // presencial
            'fecha_atencion'       => $fechaAtencion, // en línea
            'hora_inicio'          => $horaInicio,
            'hora_fin'             => $horaFin,
            'hora_inicio_invitado' => $horaInicioInvitado,
            'hora_fin_invitado'    => $horaFinInvitado,
            'numero_inv'           => $next,
            'asistio'              => null,
            'tipo_proceso_id'      => $this->tipoProcesoId, // etapa actual
            'estatus_id'           => 5,
            'acudiran_juntos'      => ! $esSeparados,
            'acepta_proceso'       => null, // Etapa 1: aceptó mediación | Etapa 2: hubo acuerdo
        ]);

        // Correos (solo en línea con URL)
        if ($this->modalidad === 'linea' && $url) {
            $horarioSolic = $this->formatoHorario($horaInicio, $horaFin);
            $horarioInv   = $this->formatoHorario($horaInicioInvitado, $horaFinInvitado);
            $fechaTxt     = $fechaAtencion ?: ($ev->fecha ?? '—');

            foreach ($this->correosSolicitantes as $correo) {
                Mail::to($correo)->send(new InvitacionMediacion('Solicitante', $url, $horarioSolic, $fechaTxt));
            }
            $horarioParaInv = $esSeparados ? $horarioInv : $horarioSolic;
            foreach ($this->correosInvitados as $correo) {
                Mail::to($correo)->send(new InvitacionMediacion('Invitado', $url, $horarioParaInv, $fechaTxt));
            }
        }

        // Refrescar + limpiar
        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();
        $this->reset([
            'enlaceReunion','urlNuevaInv',
            'fechaAtencion','fechaNuevaInv','fechaEnvio',
            'horaInicio','horaFin','horaInicioInvitado','horaFinInvitado',
        ]);

        Toaster::success(($this->tipoProcesoId === 2 ? 'Sesión' : 'Invitación') . " #{$next} guardada.");
    }

    /** Registrar (o cambiar) asistencia en una invitación/sesión */
    public function marcarAsistencia(int $invitacionId, bool $valor): void
    {
        $inv = Invitacion::where('solicitud_id', $this->solicitudId)->whereKey($invitacionId)->first();
        if (!$inv) { Toaster::error('Registro no encontrado.'); return; }

        $inv->update([
            'asistio'        => $valor,
            'acepta_proceso' => null,
        ]);

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();
        Toaster::success('Asistencia actualizada.');
    }

    /**
     * Confirmar resultado de la etapa:
     * - Etapa 1 (Pre-mediación): ¿Aceptó mediación? (si NO => motivo)
     * - Etapa 2 (Mediación): ¿Se llegó a un convenio/acuerdo? (solo registrar sí/no)
     */
   public function confirmarResultadoEtapa()
{
    $this->cargarTipoProceso();

    $ultima = $this->ultimaInvitacion();
    if (!$ultima) {
        Toaster::error('No hay registros.');
        return;
    }
    if ($ultima->asistio !== 1) {
        Toaster::error('Solo puedes confirmar el resultado cuando la última tuvo asistencia.');
        return;
    }
    if ($this->aceptoProceso === null) {
        Toaster::error('Selecciona una opción (Sí/No).');
        return;
    }

    $valor = (int) $this->aceptoProceso;
    $this->solicitud = Solicitud::find($this->solicitudId);

    // Etapa 1: Pre-mediación
    if ((int) $this->tipoProcesoId === 1) {
        if ($valor === 0) {
            // Si no acepta, validar motivo de cancelación
            $this->validate([
                'motivoCancelacion' => ['required', 'integer'],
            ], [
                'motivoCancelacion.required' => 'Selecciona un motivo de cancelación.',
            ]);
        } else {
            // Si acepta, validar manifestaciones
            $this->validate([
                'manifestaciones' => ['required'],
            ], [
                'manifestaciones.required' => 'Es requerido al menos un archivo.',
            ]);

            // Guardar documentos de manifestaciones
            if ($this->manifestaciones) {
                foreach ($this->manifestaciones as $archivo) {
                    $this->guardarDocumentoIndividual(
                        $archivo,
                        'manifestacion',
                        $this->solicitudId
                    );
                }
            }
        }

        // Guardar decisión
        $ultima->update(['acepta_proceso' => $valor]);

        if ($this->solicitud) {
            if ($valor === 1) {
                // Acepta pasar a Mediación
                $this->solicitud->update([
                    'tipo_proceso_id'     => 2,
                    'tipo_cancelacion_id' => null,
                    'facilitador_id'      => null,
                    'acudiran_juntos'     => 1,
                ]);
                $this->solicitud->refresh();
                $this->tipoProcesoId = 2;

                return Redirect::route('solicitudes.lista')
                    ->success('Registrado: Aceptó mediación. El proceso cambió a Mediación.');

                $this->dispatch('proceso-actualizado', id: $this->solicitudId);
            } else {
                // No aceptó mediación
                $this->solicitud->update([
                    'tipo_cancelacion_id' => $this->motivoCancelacion,
                ]);
                $this->solicitud->refresh();
                Toaster::success('Registrado: No aceptó mediación. Motivo guardado.');
            }
        }
    }
    // Etapa 2: Mediación (¿se llegó a acuerdo?)
    else {
        $ultima->update(['acepta_proceso' => $valor]); // reutilizamos como "acuerdo alcanzado"

        if ($this->solicitud) {
            if ($valor === 1) {
                // Sí hubo acuerdo/convenio
                Toaster::success('Registrado: Se llegó a un convenio/acuerdo.');
                $this->solicitud->update([
                    'tipo_proceso_id'     => 3,
                    'tipo_cancelacion_id' => null,
                ]);
                $this->solicitud->refresh();
                $this->dispatch('proceso-actualizado', id: $this->solicitudId);
            } else {
                // No hubo acuerdo
                Toaster::success('Registrado: No hubo convenio/acuerdo.');
            }
        }
    }

    // Refrescar estado/UI
    $this->cargarInvitaciones();
    $this->sincronizarEstadoUI();

    // limpiar UI
    $this->aceptoProceso = null;
    $this->motivoCancelacion = null;
}

  protected function guardarDocumentoIndividual($archivo, $tipo, $solicitudId, $guardarDB = true)
    {
        if (!$archivo || empty($archivo['path']) || !is_file($archivo['path'])) {
            return null;
        }

        $nombreOriginal = $archivo['name'];
        $nuevoNombre    = uniqid('', true) . '_' . $nombreOriginal;
        $destino        = 'documentos';

        $rutaFinal = Storage::disk('public')->putFileAs(
            $destino,
            new HttpFile($archivo['path']),
            $nuevoNombre
        );

        $rutaPublica = 'storage/' . $rutaFinal;

        if ($guardarDB) {
            DocumentoSolicitud::create([
                'solicitud_id'    => $solicitudId,
                'tipo'            => $tipo,
                'nombre_original' => $nombreOriginal,
                'ruta'            => $rutaPublica,
                'extension'       => $archivo['extension'] ?? pathinfo($nombreOriginal, PATHINFO_EXTENSION),
                'size'            => $archivo['size'] ?? null,
            ]);
        }

        return $rutaPublica;
    }

    /** Etiqueta singular según etapa (para mensajes) */
    private function etiquetaSing(): string
    {
        return ((int)($this->tipoProcesoId ?? 0) === 2) ? 'sesión' : 'invitación';
    }

    public function render()
    {
        return view('livewire.solicitud.invitaciones-panel');
    }
}
