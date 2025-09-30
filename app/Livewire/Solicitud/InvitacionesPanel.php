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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\Facilitador;
use App\Models\Catalogos\CatMotivosCierre;
use App\Models\CatCancelacion;

class InvitacionesPanel extends Component
{
    // Props de entrada
    public ?Agenda $evento = null;                 // Evento de Pre-mediación (1ra)
    public ?Agenda $eventoSegPreMedicion = null;   // Evento de Reasignación (2da)
    public int $solicitudId;
    public string $modalidad; // 'linea' | 'presencial' (UI); internamente también se acepta 2/1

    // Modelo cargado
    public ?Solicitud $solicitud = null;

    // Form (1ª o N-ésima) — SOLO Pre
    public ?string $enlaceReunion = null;     // 1ª en línea
    public ?string $urlNuevaInv   = null;     // N-ésima en línea
    public ?string $fechaNuevaInv = '';       // Pre en línea / presencial (N-ésima)
    public ?string $fechaEnvio    = '';       // 1ª presencial

    // Horarios (inputs)
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

    /** UI: pregunta de resultado (solo Pre) */
    public bool $mostrarPreguntaAceptacion = false;
    public ?int $aceptoProceso = null; // Pre: aceptó mediación
    public $motivoCancelacion = '';

    /** Tipo de proceso actual (SIEMPRE 1 = Pre-mediación) */
    public ?int $tipoProcesoId = 1;

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
    public $motivoCierreId = '';
    public $motivosCierre = '';
    public $notas_observaciones = '';

    /** Config máximos */
    public $maxInvPre = 2;

    protected $rules = [
        'enlaceReunion' => ['nullable', 'url'],
        'urlNuevaInv'   => ['nullable', 'url'],
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
        $this->motivosCierre       = $this->motivosCierre();

        // Forzar etapa actual a PRE (1)
        $this->tipoProcesoId = 1;

        $this->cargarCorreos();
        $this->cargarInvitaciones();

        // Evento de reasignación (2ª invitación de Pre)
        $this->eventoSegPreMedicion = Agenda::query()
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 1)
            ->whereNull('invitacion_id')
            ->orderBy('fecha')
            ->first();

        $this->sincronizarEstadoUI();

        // Precarga de horas solo para la primera
        $nextPre     = (int) ($this->invitaciones->max('numero_inv') ?? 0) + 1;
        if ($nextPre === 1 && $this->evento) {
            $this->precargarDesdeEvento($this->evento, true);
        } else {
            $this->horaInicio = $this->horaFin = $this->horaInicioInvitado = $this->horaFinInvitado = '';
        }
    }

    public function motivosCierre()
    {
        return CatCancelacion::orderBy('motivo')->get(['id', 'motivo']);
    }

    private function precargarDesdeEvento(Agenda $ev, bool $tambienFechas = true): void
    {
        // intencionalmente vacío
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

    private function cargarInvitaciones(): void
    {
        $this->invitaciones = Invitacion::with('evento', 'facilitador')
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 1)
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->get();
    }

    private function ultimaInvitacion(): ?Invitacion
    {
        return Invitacion::with('evento')
            ->where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 1)
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
            $this->motivoCancelacion = '';
        }
    }

    // ===== Helpers usados desde la vista (sin @php) =====

    public function formatHora(?string $t): string
    {
        return $t ? Carbon::parse($t)->format('H:i') : '—';
    }

    public function evForInv(?Invitacion $inv): ?Agenda
    {
        if (!$inv) return null;
        if ($inv->evento) return $inv->evento;

        if ($inv->numero_inv == 1) return $this->evento;
        if ($inv->numero_inv == 2) return $this->eventoSegPreMedicion ?: $this->evento;

        return $this->evento;
    }

    public function isSeparados(?Agenda $ev): bool
    {
        return (int)($ev->opcion_invitacion ?? 1) === 0;
    }

    public function chipAsistencia(?Invitacion $inv): array
    {
        if (!$inv || is_null($inv->asistio)) {
            return ['label' => 'Asistencia: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300'];
        }
        return $inv->asistio
            ? ['label' => 'Asistencia: Sí', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => 'Asistencia: No', 'class' => 'bg-red-100 text-red-700 dark:bg-red-700/20 dark:text-red-300'];
    }

    public function chipAcepto(?Invitacion $inv): array
    {
        if (!$inv || is_null($inv->acepta_proceso)) {
            return ['label' => 'Aceptación: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300'];
        }
        return $inv->acepta_proceso
            ? ['label' => 'Aceptó mediación', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => 'No aceptó mediación', 'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300'];
    }

    public function chipAsistenciaRow(?Invitacion $inv): array
    {
        if (!$inv || is_null($inv->asistio)) {
            return ['Pendiente', 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'];
        }
        return $inv->asistio
            ? ['Asistió', 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200']
            : ['No asistió', 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200'];
    }

    public function dotClass(?Invitacion $inv): string
    {
        if (!$inv || is_null($inv->asistio)) return 'bg-amber-400';
        return $inv->asistio ? 'bg-emerald-500' : 'bg-rose-500';
    }

    // ===== Lógica del Blade =====
    public function getVistaData(): array
    {
        $invitaciones = $this->invitaciones ?? collect();
        $ultima       = $invitaciones->sortByDesc('numero_inv')->first();
        $next         = ($invitaciones->max('numero_inv') ?? 0) + 1;
        $isPrimera    = $invitaciones->isEmpty();

        $maxInvPre = $this->maxInvPre ?? 2;

        // === Cancelación presente ===
        $hayCancelacion = !is_null($this->solicitud->tipo_cancelacion_id);

        // === Flags de bloqueo ===
        $bloqueoLimitePre     = $next > $maxInvPre;
        $bloqueoAsistencia    = ($ultima && is_null($ultima->asistio));
        $requiereAceptarAntes = ($ultima && (int)$ultima->asistio === 1 && is_null($ultima->acepta_proceso));
        $bloqueoPorAcepto     = ($ultima && (int)$ultima->asistio === 1 && (int)$ultima->acepta_proceso === 1);
        $esSegundaPre         = $next == 2;

        $bloqueoReasignacionSegPre = $esSegundaPre
            && (($ultima && (int)$ultima->numero_inv === 1 && (int)$ultima->asistio === 0)
                ? !$this->eventoSegPreMedicion
                : true);

        // === Inicialización ===
        $motivoBloqueo = '';
        $bloqueoNueva  = false;

        // === Reglas de bloqueo (orden importa) ===
        if ($bloqueoLimitePre) {
            $motivoBloqueo = "En Pre-mediación solo se permiten {$maxInvPre} invitaciones.";

        } elseif ($hayCancelacion) {
            $bloqueoNueva   = true;
            $motivoBloqueo  = 'El registro se cierra porque no se aceptó continuar con el proceso.';

        } elseif ($bloqueoAsistencia) {
            $motivoBloqueo = "Primero registra la asistencia de la última invitación.";

        } elseif ($requiereAceptarAntes) {
            $motivoBloqueo = 'Confirma si aceptaron mediación antes de crear otra invitación.';

        } elseif ($bloqueoPorAcepto) {
            $bloqueoNueva  = true;
            $motivoBloqueo = 'Ya aceptaron mediación. No se permiten nuevas invitaciones.';

        } elseif ($bloqueoReasignacionSegPre) {
            if ($esSegundaPre && $ultima) {
                if ((int)$ultima->numero_inv === 1 && (int)$ultima->asistio === 0) {
                    $motivoBloqueo = 'No se puede crear la 2ª invitación: primero debes generar un evento de reasignación por la inasistencia en la invitación #1.';
                } elseif ((int)$ultima->asistio === 1 && (int)$ultima->acepta_proceso === 0) {
                    $motivoBloqueo = 'El registro se cierra: la mediación no fue aceptada.';
                } else {
                    $motivoBloqueo = 'La 2ª invitación solo procede cuando la invitación #1 fue marcada como "No asistió".';
                }
            }
        }

        // Si hay cancelación, forzamos bloqueoNueva aunque no haya caído en el branch anterior
        $bloqueoNueva = $bloqueoNueva
            || $hayCancelacion
            || $bloqueoLimitePre
            || $bloqueoAsistencia
            || $requiereAceptarAntes
            || $bloqueoPorAcepto
            || $bloqueoReasignacionSegPre;

        // Acción y texto de botón
        $accionClick = $isPrimera ? 'primera' : 'nueva';
        $textoBtn    = $isPrimera ? "Enviar invitación" : "Enviar invitación #{$next}";

        // Chips de estado
        $chipProceso = [
            'label' => "Pre-mediación • máx. {$maxInvPre}",
            'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'
        ];

        $chipAsistencia = $this->chipAsistencia($ultima);
        $chipAcepto     = $this->chipAcepto($ultima);

        // Evento/horarios
        $evEtapa         = $this->evento;
        $fechaAtEvento   = $evEtapa->fecha ?? null;
        $opcionSeparados = (int)($evEtapa->opcion_invitacion ?? 1) === 0;

        // Mostrar pregunta de aceptación (SOLO si NO hay cancelación)
        $mostrarPreguntaAceptacion = !$hayCancelacion && (
            $this->mostrarPreguntaAceptacion
            || ($ultima && (int)$ultima->asistio === 1 && is_null($ultima->acepta_proceso))
        );

        // Tooltip
        $tooltipMsg = $hayCancelacion
            ? 'Registro cancelado. No se puede continuar.'
            : ($mostrarPreguntaAceptacion
                ? 'Primero registra la aceptación / rechazo.'
                : ($bloqueoAsistencia ? 'Primero registra la asistencia.' : $motivoBloqueo));

        return [
            'steps' => [1 => 'Pre-mediación'],
            'current' => 1,
            'isPrimera' => $isPrimera,
            'next' => $next,
            'ultima' => $ultima,
            'esSegundaPre' => $esSegundaPre,
            'maxInvPre' => $maxInvPre,
            'bloqueoNueva' => $bloqueoNueva,
            'bloqueoAsistencia' => $bloqueoAsistencia,
            'requiereAceptarAntes' => $requiereAceptarAntes,
            'motivoBloqueo' => $motivoBloqueo,
            'etqUnidadSing' => 'invitación',
            'etqUnidadPlural' => 'invitaciones',
            'evEtapa' => $evEtapa,
            'fechaAtEvento' => $fechaAtEvento,
            'opcionSeparados' => $opcionSeparados,
            'accionClick' => $accionClick,
            'textoBtn' => $textoBtn,
            'chipProceso' => $chipProceso,
            'chipAsistencia' => $chipAsistencia,
            'chipAcepto' => $chipAcepto,
            'mostrarPreguntaAceptacionVista' => $mostrarPreguntaAceptacion,
            'tooltipMsg' => $tooltipMsg,
            'textoPregunta' => '¿Aceptó mediación?',
            'labelFecha' => 'Fecha de atención',
            'hayCancelacion' => $hayCancelacion,
        ];
    }



    // =================== Acciones (sin cambios de lógica de negocio) ===================

    private function formatoHorario(?string $inicio, ?string $fin): string
    {
        if (!$inicio || !$fin) return '—';
        return Carbon::parse($inicio)->format('H:i') . ' - ' . Carbon::parse($fin)->format('H:i');
    }

    public function store(string $context = 'primera'): void
    {
        $this->solicitud = Solicitud::find($this->solicitudId);
        if (!$this->solicitud) {
            Toaster::error('No se encontró la solicitud.');
            return;
        }

        if (is_null($this->solicitud->tipo_proceso_id)) {
            $this->solicitud->update(['tipo_proceso_id' => 1]);
            $this->solicitud->refresh();
        }

        $ultima = $this->ultimaInvitacion();

        if ($ultima && is_null($ultima->asistio)) {
            Toaster::warning("Registra la asistencia de la invitación #{$ultima->numero_inv} antes de crear una nueva.");
            return;
        }

        $next = (int)(Invitacion::where('solicitud_id', $this->solicitudId)
            ->where('tipo_proceso_id', 1)
            ->max('numero_inv') ?? 0) + 1;

        if ($next > $this->maxInvPre) {
            Toaster::warning("En Pre-mediación solo se permiten {$this->maxInvPre} invitaciones.");
            return;
        }

        if ($next >= 2 && $ultima) {
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

        $ev = ($next >= 2)
            ? ($this->eventoSegPreMedicion ?: $this->evento)
            : $this->evento;

        $rules = [];
        $opcionSeparados = (int)($ev->opcion_invitacion ?? 1) === 0;

        // Nota: Para compatibilidad, tratamos modalidad 'linea' como 2
        $isLinea = ($this->modalidad === 'linea' || $this->modalidad == 2);

        if ($isLinea) {
            if ($context === 'nueva') {
                $rules['urlNuevaInv'] = ['required', 'url'];
            } else {
                $rules['enlaceReunion'] = ['required', 'url'];
            }
        } else {
            if ($context === 'nueva') {
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['fechaEnvio']    = ['required', 'date'];
            }
            $rules['horaInicio'] = ['required'];
        }

        $this->validate($rules);

        $url = $isLinea
            ? ($context === 'nueva' ? $this->urlNuevaInv : $this->enlaceReunion)
            : null;

        $fechaAtencion = null;
        $fechaEnvio    = null;

        if ($isLinea) {
            $fechaAtencion = $ev->fecha ?? null;
            if (!$fechaAtencion && $context === 'nueva') {
                $fechaAtencion = $this->fechaNuevaInv ?: null;
            }
            if (!$fechaAtencion) {
                Toaster::warning('La fecha de atención no está definida.');
                return;
            }
        } else {
            $fechaEnvio = ($context === 'nueva')
                ? ($this->fechaNuevaInv ?: null)
                : ($this->fechaEnvio ?: null);

            if (!$fechaEnvio) {
                Toaster::warning('Captura la fecha de envío.');
                return;
            }
        }

        $facilitadorId = $this->solicitud->facilitador_id;
        if ($facilitadorId && !User::whereKey($facilitadorId)->exists()) {
            $facilitadorId = null;
        }

        $esSeparados = $isLinea && $opcionSeparados;

        if ($isLinea) {
            $horaInicio          = $ev->hora_inicio ?? null;
            $horaFin             = $ev->hora_fin ?? null;
            $horaInicioInvitado  = $esSeparados ? ($ev->hora_inicio_invitado ?? null) : null;
            $horaFinInvitado     = $esSeparados ? ($ev->hora_fin_invitado ?? null) : null;
        } else {
            $horaInicio          = $this->horaInicio ?: null;
            $horaFin             = $this->horaFin ?: null;
            $horaInicioInvitado  = null;
            $horaFinInvitado     = null;
        }

        $inv = Invitacion::create([
            'modalidad'            => $this->modalidad,
            'solicitud_id'         => $this->solicitudId,
            'facilitador_id'       => $facilitadorId,
            'url'                  => $url,
            'fecha_envio'          => $fechaEnvio,
            'fecha_atencion'       => $fechaAtencion,
            'hora_inicio'          => $horaInicio,
            'hora_fin'             => $horaFin,
            'hora_inicio_invitado' => $horaInicioInvitado,
            'hora_fin_invitado'    => $horaFinInvitado,
            'numero_inv'           => $next,
            'asistio'              => null,
            'tipo_proceso_id'      => 1,
            'estatus_id'           => 5,
            'acudiran_juntos'      => !$esSeparados,
            'acepta_proceso'       => null,
        ]);

        if ($next === 1 && $this->evento) {
            $this->evento->update(['invitacion_id' => $inv->id]);
        }
        if ($next === 2 && $this->eventoSegPreMedicion) {
            $this->eventoSegPreMedicion->update(['invitacion_id' => $inv->id]);
        }

        if ($isLinea && $url) {
            $horarioSolic = $this->formatoHorario($inv->hora_inicio, $inv->hora_fin);
            $horarioInv   = $esSeparados
                ? $this->formatoHorario($inv->hora_inicio_invitado, $inv->hora_fin_invitado)
                : $horarioSolic;

            $fechaTxt = $inv->fecha_atencion ?: ($ev->fecha ?? '—');

            foreach ($this->correosSolicitantes as $correo) {
                Mail::to($correo)->send(
                    new InvitacionMediacion('Solicitante', $url, $horarioSolic, $fechaTxt, $next, 1)
                );
            }
            foreach ($this->correosInvitados as $correo) {
                Mail::to($correo)->send(
                    new InvitacionMediacion('Invitado', $url, $horarioInv, $fechaTxt, $next, 1)
                );
            }
        }

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();
        $this->reset([
            'enlaceReunion',
            'urlNuevaInv',
            'fechaNuevaInv',
            'fechaEnvio',
            'horaInicio',
            'horaFin',
            'horaInicioInvitado',
            'horaFinInvitado',
        ]);

        Toaster::success("Invitación #{$next} guardada.");
    }

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

        if ($valor === 0) {
            $this->validate([
                'motivoCancelacion' => ['required', 'integer'],
            ], [
                'motivoCancelacion.required' => 'Selecciona un motivo de cancelación.',
            ]);

            $ultima->update(['acepta_proceso' => $valor]);

            if ($this->solicitud) {
                $this->solicitud->update([
                    'tipo_cancelacion_id' => $this->motivoCancelacion,
                ]);
                $this->solicitud->refresh();
                Toaster::success('Registrado: No aceptó mediación. Motivo guardado.');
            }
        } else {
            $this->validate([
                'manifestaciones' => ['required'],
            ], [
                'manifestaciones.required' => 'Es requerido al menos un archivo.',
            ]);

            if ($this->manifestaciones) {
                foreach ($this->manifestaciones as $archivo) {
                    $this->guardarDocumentoIndividual($archivo, 'manifestacion', $this->solicitudId);
                }
            }

            $ultima->update(['acepta_proceso' => $valor]);
            $ultima->update(['notas_observaciones' => $this->notas_observaciones]);

            if ($this->solicitud) {
                $this->solicitud->update([
                    'tipo_cancelacion_id' => null,
                ]);
                $this->solicitud->refresh();
                Toaster::success('Registrado: Aceptó mediación.');
            }
        }

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

        $this->aceptoProceso = null;
        $this->motivoCancelacion = null;
    }

    public function cancelarRegistro()
    {
        if ($this->solicitud) {
            $this->solicitud->update([
                'tipo_cancelacion_id' => $this->motivoCancelacion,
            ]);
            $this->solicitud->refresh();
            Toaster::success('Registro Cancelado.');
            $this->modal('cancelar-registro')->close();
        }
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

    private function etiquetaSing(): string
    {
        return 'invitación';
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
            'tipo_proceso_id'      => 1,
            'activo'               => 1,
            'observacion'          => '',
            'invitacion_id'        => null
        ]);

        Solicitud::whereKey($this->solicitudId)->update([
            'estatus_id'     => 2,
            'facilitador_id' => $this->facilitador,
        ]);

        $this->eventoSegPreMedicion = $eventoNuevo;
        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

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
        return view('livewire.solicitud.invitaciones-panel', $this->getVistaData());
    }
}
