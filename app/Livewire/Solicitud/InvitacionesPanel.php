<?php

namespace App\Livewire\Solicitud;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agenda;
use App\Models\Correo;
use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Invitacion;
use App\Models\Facilitador;
use Masmerise\Toaster\Toaster;
use App\Livewire\Concerns\UiText;
use App\Mail\InvitacionMediacion;
use App\Models\DocumentoSolicitud;
use Illuminate\Support\Collection;
use App\Livewire\Concerns\Bloqueos;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Concerns\EtapasState;
use App\Livewire\Concerns\InvCounters;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Livewire\Concerns\EventosAccessors;

class InvitacionesPanel extends Component
{
    use EtapasState, InvCounters, EventosAccessors, Bloqueos, UiText;

    // Props de entrada
    public ?Agenda $evento = null;                 // Evento de Pre-mediación (1ra invitación)
    public ?Agenda $eventoMediacion = null;        // Evento de Mediación
    public ?Agenda $eventoSegPreMedicion = null;   // Evento de Reasignación (2da invitación Pre)
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

    /** UI: pregunta de resultado */
    public bool $mostrarPreguntaAceptacion = false;
    public ?int $aceptoProceso = null;        // Pre: aceptó mediación ; Mediación: hubo acuerdo
    public ?int $motivoCancelacion = null;    // Solo Pre-mediación cuando NO acepta

    /** Tipo de proceso actual */
    public ?int $tipoProcesoId = null;

    /** Config: máximos por etapa */


    /** Estatus (solo 5) */
    public array $estatusLabels = [5 => 'Activo'];
    public $manifestaciones = null;

    /** Para nuevo evento 2da invitacion etc */
    public $facilitadores;
    public $facilitador = '';
    public $fechaNueva;
    public $horaInicioEvento = '';
    public $horaFinEvento = '';
    public $horaInicioInvitadoEvento = '';
    public $horaFinInvitadoEvento = '';
    public $colorEvento = '';
    public $eventoSegunda = '';

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

        $this->facilitadores = Cache::remember('facilitadores_list_v1', 600, function () {
            return Facilitador::select('id', 'nombre')->get();
        });

        $this->solicitud           = Solicitud::find($this->solicitudId);
        $this->correosSolicitantes = collect();
        $this->correosInvitados    = collect();
        $this->horarios            = $this->generarHorarios('09:00', '19:00');

        $this->cargarTipoProceso();
        $this->cargarCorreos();
        $this->cargarInvitaciones(); // <- trae invitaciones con ->evento

        // Identificar el evento de reasignación (Pre, 2ª invitación)
        $this->eventoSegPreMedicion = Agenda::query()
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 1)
            ->whereNull('invitacion_id')
            ->orderBy('fecha')
            ->first();

        // Evento mediación
        $this->eventoMediacion = Agenda::query()
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 2)
            ->orderBy('fecha')
            ->first();

        $this->sincronizarEstadoUI();

        // Precargas de horas para la primera de cada etapa (si quisieras)
        $nextEtapa = (int) ($this->invitaciones->max('numero_inv') ?? 0) + 1;
        $esPre     = (int)($this->tipoProcesoId ?? 0) === 1;
        $esMed     = (int)($this->tipoProcesoId ?? 0) === 2;

        $esPrimeraPre = $esPre && $nextEtapa === 1;
        $esPrimeraMed = $esMed && $nextEtapa === 1;

        if ($esPrimeraPre && $this->evento) {
            $this->precargarDesdeEvento($this->evento, true);
        } elseif ($esPrimeraMed && $this->eventoMediacion) {
            $this->precargarDesdeEvento($this->eventoMediacion, false);
        } else {
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
        // (intencionalmente comentado para no sobreescribir los inputs)
        if ($tambienFechas) {
            // $this->fechaAtencion = $ev->fecha ?? null;
            // $this->fechaEnvio    = $ev->fecha ?? null;
        }
    }

    private function cargarTipoProceso(): void
    {
        $this->tipoProcesoId = Solicitud::whereKey($this->solicitudId)->value('tipo_proceso_id');
        if ($this->tipoProcesoId !== null) $this->tipoProcesoId = (int) $this->tipoProcesoId;
    }

    private function trimHi(?string $t): ?string
    {
        return $t ? substr($t, 0, 5) : null;
    }

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

    /** Invitaciones SOLO de la etapa actual (con el evento relacionado) */
    private function cargarInvitaciones(): void
    {
        $this->cargarTipoProceso();

        $this->invitaciones = Invitacion::with('evento')        // <-- eager load del evento
            ->where('solicitud_id', $this->solicitudId)
            ->when($this->tipoProcesoId !== null, fn($q) => $q->where('tipo_proceso_id', $this->tipoProcesoId))
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->get();
    }

    /** Última invitación SOLO de la etapa actual */
    private function ultimaInvitacion(): ?Invitacion
    {
        $this->cargarTipoProceso();

        return Invitacion::with('evento')
            ->where('solicitud_id', $this->solicitudId)
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

    // === NUEVO: Horarios para correo SIEMPRE desde el EVENTO
    private function horariosEventoParaCorreo(?Agenda $ev, bool $separados): array
    {
        if (!$ev) return ['—', '—'];

        $solicitante = $this->formatoHorario($ev->hora_inicio, $ev->hora_fin);
        if ($separados) {
            $invitado = $this->formatoHorario($ev->hora_inicio_invitado, $ev->hora_fin_invitado);
        } else {
            $invitado = $solicitante;
        }

        return [$solicitante, $invitado];
    }

   public function store(string $context = 'primera'): void
{
    $this->cargarTipoProceso();
    $this->solicitud = Solicitud::find($this->solicitudId);
    if (!$this->solicitud) {
        Toaster::error('No se encontró la solicitud.');
        return;
    }

    // Si no hay etapa, arrancamos en Pre-mediación (1)
    if ($this->tipoProcesoId === null) {
        $this->tipoProcesoId = 1;
        $this->solicitud->update(['tipo_proceso_id' => 1]);
        $this->solicitud->refresh();
    }

    $ultima = $this->ultimaInvitacion();

    // 1) Si existe última sin asistencia registrada -> bloquear
    if ($ultima && is_null($ultima->asistio)) {
        Toaster::warning("Registra la asistencia de la {$this->etiquetaSing()} #{$ultima->numero_inv} antes de crear una nueva.");
        return;
    }

    // 2) Consecutivo por etapa
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

    // ===== Evento fuente CORRECTO según # y etapa =====
    // Pre: #1 -> $this->evento ; #2 -> $this->eventoSegPreMedicion (si existe; si no, fallback a $this->evento)
    // Mediación: siempre $this->eventoMediacion
    if ((int)$this->tipoProcesoId === 1) {
        $ev = ($next >= 2)
            ? ($this->eventoSegPreMedicion ?: $this->evento)
            : $this->evento;
    } else {
        $ev = $this->eventoMediacion;
    }

    // 5) Validación dinámica por modalidad/contexto
    $rules = [];
    $opcionSeparados = (int)($ev->opcion_invitacion ?? 1) === 0; // 0=separados, 1=juntos

    if ($this->modalidad === 'linea') {
        // En línea: URL obligatoria; fecha/horarios salen del EVENTO correcto
        if ($context === 'nueva') {
            $rules['urlNuevaInv'] = ['required', 'url'];
        } else {
            $rules['enlaceReunion'] = ['required', 'url'];
        }
    } else {
        // Presencial
        if ($context === 'nueva') {
            $rules['fechaNuevaInv'] = ['required', 'date'];
        } else {
            $rules['fechaEnvio']    = ['required', 'date'];
        }
        $rules['horaInicio'] = ['required']; // hora de envío
    }

    $this->validate($rules);

    // Inputs según modalidad/contexto
    $url = $this->modalidad === 'linea'
        ? ($context === 'nueva' ? $this->urlNuevaInv : $this->enlaceReunion)
        : null;

    // ===== Fechas =====
    $fechaAtencion = null;
    $fechaEnvio    = null;

    if ($this->modalidad === 'linea') {
        // Tomar SIEMPRE del evento correcto; si no hay y es "nueva", usar capturada
        $fechaAtencion = $ev->fecha ?? null;
        if (!$fechaAtencion && $context === 'nueva') {
            $fechaAtencion = $this->fechaNuevaInv ?: null;
        }
        if (!$fechaAtencion) {
            Toaster::warning('La fecha de atención no está definida en el evento ni fue capturada.');
            return;
        }
    } else {
        // Presencial
        $fechaEnvio = ($context === 'nueva')
            ? ($this->fechaNuevaInv ?: null)
            : ($this->fechaEnvio ?: null);

        if (!$fechaEnvio) {
            Toaster::warning('Captura la fecha de envío.');
            return;
        }
    }

    // FK segura del facilitador
    $facilitadorId = $this->solicitud->facilitador_id;
    if ($facilitadorId && !User::whereKey($facilitadorId)->exists()) {
        $facilitadorId = null;
    }

    // ===== Horarios a guardar =====
    $esSeparados = ($this->modalidad === 'linea') && $opcionSeparados;

    if ($this->modalidad === 'linea') {
        // SIEMPRE desde el evento correcto
        $horaInicio          = $ev->hora_inicio ?? null;
        $horaFin             = $ev->hora_fin ?? null;
        $horaInicioInvitado  = $opcionSeparados ? ($ev->hora_inicio_invitado ?? null) : null;
        $horaFinInvitado     = $opcionSeparados ? ($ev->hora_fin_invitado ?? null) : null;
    } else {
        // Presencial: hora de envío capturada
        $horaInicio          = $this->horaInicio ?: null;
        $horaFin             = $this->horaFin ?: null;
        $horaInicioInvitado  = null;
        $horaFinInvitado     = null;
    }

    // Crear invitación/sesión
    $inv = Invitacion::create([
        'modalidad'            => $this->modalidad,
        'solicitud_id'         => $this->solicitudId,
        'facilitador_id'       => $facilitadorId,
        'url'                  => $url,
        'fecha_envio'          => $fechaEnvio,     // presencial
        'fecha_atencion'       => $fechaAtencion,  // en línea
        'hora_inicio'          => $horaInicio,
        'hora_fin'             => $horaFin,
        'hora_inicio_invitado' => $horaInicioInvitado,
        'hora_fin_invitado'    => $horaFinInvitado,
        'numero_inv'           => $next,
        'asistio'              => null,
        'tipo_proceso_id'      => $this->tipoProcesoId,
        'estatus_id'           => 5,
        'acudiran_juntos'      => !$esSeparados,
        'acepta_proceso'       => null,
    ]);

    // Enlazar evento->invitacion_id
    if ((int)$this->tipoProcesoId === 1) {
        if ($next === 1 && $this->evento) {
            $this->evento->update(['invitacion_id' => $inv->id]);
        }
        if ($next === 2 && $this->eventoSegPreMedicion) {
            $this->eventoSegPreMedicion->update(['invitacion_id' => $inv->id]);
        }
    } elseif ((int)$this->tipoProcesoId === 2 && $this->eventoMediacion && !$this->eventoMediacion->invitacion_id) {
        $this->eventoMediacion->update(['invitacion_id' => $inv->id]);
    }

    // ===== Correos (solo en línea con URL) — horarios SIEMPRE del EVENTO correcto =====
    if ($this->modalidad === 'linea' && $url) {
        $horarioSolic = $this->formatoHorario($ev->hora_inicio ?? null, $ev->hora_fin ?? null);
        $horarioInv   = $esSeparados
            ? $this->formatoHorario($ev->hora_inicio_invitado ?? null, $ev->hora_fin_invitado ?? null)
            : $horarioSolic;

        $fechaTxt = $ev->fecha ?: $fechaAtencion ?: '—';

        foreach ($this->correosSolicitantes as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Solicitante', $url, $horarioSolic, $fechaTxt));
        }
        foreach ($this->correosInvitados as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Invitado', $url, $horarioInv, $fechaTxt));
        }
    }

    // Refrescar + limpiar
    $this->cargarInvitaciones();
    $this->sincronizarEstadoUI();
    $this->reset([
        'enlaceReunion',
        'urlNuevaInv',
        'fechaAtencion',
        'fechaNuevaInv',
        'fechaEnvio',
        'horaInicio',
        'horaFin',
        'horaInicioInvitado',
        'horaFinInvitado',
    ]);

    Toaster::success(($this->tipoProcesoId === 2 ? 'Sesión' : 'Invitación') . " #{$next} guardada.");
}


    /** Registrar (o cambiar) asistencia en una invitación/sesión */
    public function marcarAsistencia(int $invitacionId, bool $valor): void
    {
        $inv = Invitacion::where('solicitud_id', $this->solicitudId)->whereKey($invitacionId)->first();
        if (!$inv) {
            Toaster::error('Registro no encontrado.');
            return;
        }

        $inv->update([
            'asistio'        => $valor,
            'acepta_proceso' => null,
        ]);

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();
        Toaster::success('Asistencia actualizada.');
    }

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
                $this->validate([
                    'motivoCancelacion' => ['required', 'integer'],
                ], [
                    'motivoCancelacion.required' => 'Selecciona un motivo de cancelación.',
                ]);
            } else {
                $this->validate([
                    'manifestaciones' => ['required'],
                ], [
                    'manifestaciones.required' => 'Es requerido al menos un archivo.',
                ]);

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

            $ultima->update(['acepta_proceso' => $valor]);

            if ($this->solicitud) {
                if ($valor === 1) {
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

                    // (código inalcanzable después del return)
                    // $this->dispatch('proceso-actualizado', id: $this->solicitudId);
                } else {
                    $this->solicitud->update([
                        'tipo_cancelacion_id' => $this->motivoCancelacion,
                    ]);
                    $this->solicitud->refresh();
                    Toaster::success('Registrado: No aceptó mediación. Motivo guardado.');
                }
            }
        }
        // Etapa 2: Mediación
        else {
            $ultima->update(['acepta_proceso' => $valor]);

            if ($this->solicitud) {
                if ($valor === 1) {
                    Toaster::success('Registrado: Se llegó a un convenio/acuerdo.');
                    $this->solicitud->update([
                        'tipo_proceso_id'     => 3,
                        'tipo_cancelacion_id' => null,
                    ]);
                    $this->solicitud->refresh();
                    $this->dispatch('proceso-actualizado', id: $this->solicitudId);
                } else {
                    Toaster::success('Registrado: No hubo convenio/acuerdo.');
                }
            }
        }

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

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

    public function crearEvento()
    {
        $sol = Solicitud::select('materia', 'acudiran_juntos', 'tipo_proceso_id', 'facilitador_id')->findOrFail($this->solicitudId);

        $eventoNuevo = Agenda::create([
            'solicitud_id'         => $this->solicitudId,
            'facilitador_id'       => $this->facilitador,
            'opcion_invitacion'    => (int) $sol->acudiran_juntos,
            'fecha'                => $this->fechaNueva,
            'hora_inicio'          => $this->horaInicioEvento,
            'hora_fin'             => $this->horaFinEvento,
            'hora_inicio_invitado' => $this->horaInicioInvitadoEvento ?: null,
            'hora_fin_invitado'    => $this->horaFinInvitadoEvento ?: null,
            'descripcion'          => 'Segunda Sesión',
            'materia'              => $sol->materia,
            'color'                => $this->colorEvento,
            'estatus_id'           => 2, // Asignado
            'tipo_proceso_id'      => $sol->tipo_proceso_id ?? 1,
            'activo'               => 1,
            'observacion'          => '',
            'invitacion_id'        => null
        ]);

        // CORRECCIÓN: usar solicitudId (antes whereKey($this->solicitud))
        Solicitud::whereKey($this->solicitudId)->update([
            'estatus_id'     => 2,
            'facilitador_id' => $this->facilitador,
        ]);

        $this->eventoSegPreMedicion = $eventoNuevo;
        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

        // Resetear inputs del formulario
        $this->reset([
            'facilitador',
            'fechaNueva',
            'horaInicioEvento',
            'horaFinEvento',
            'horaInicioInvitadoEvento',
            'horaFinInvitadoEvento',
            'colorEvento'
        ]);

        Toaster::success('Evento de reasignación creado y cargado.');
    }

    public function render()
    {
        return view('livewire.solicitud.invitaciones-panel');
    }
}
