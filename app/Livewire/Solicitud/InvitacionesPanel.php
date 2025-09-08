<?php

namespace App\Livewire\Solicitud;

use Livewire\Component;
use App\Models\Agenda;
use App\Models\Solicitud;
use App\Models\Invitacion;
use App\Models\Correo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitacionMediacion;
use Masmerise\Toaster\Toaster;

class InvitacionesPanel extends Component
{
    // Props de entrada
    public ?Agenda $evento = null;
    public int $solicitudId;
    public string $modalidad;

    // Form (1ª o N-ésima)
    public ?string $enlaceReunion = null;   // 1ª línea: URL
    public ?string $urlNuevaInv   = null;   // N-ésima: URL
    public ?string $fechaAtencion = null;   // 1ª línea: lectura (evento)
    public ?string $fechaNuevaInv = null;   // N-ésima (línea) / presencial (N-ésima)
    public ?string $fechaEnvio    = null;   // 1ª presencial (INPUT)

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

    /** UI: aceptación requerida */
    public bool $mostrarPreguntaAceptacion = false;
    public ?bool $aceptoProceso = null; // input de radio (Sí/No) para la última invitación asistida

    /** Tipo de proceso actual (para límite por proceso) */
    public ?int $tipoProcesoId = null;

    /** Estatus (solo 5) */
    public array $estatusLabels = [
        5 => 'Activo',
    ];

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

        $this->correosSolicitantes = collect();
        $this->correosInvitados    = collect();
        $this->horarios            = $this->generarHorarios('09:00', '19:00');

        // Cargar estado inicial
        $this->cargarTipoProceso();
        $this->cargarCorreos();
        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

        // Precarga (para mostrar)
        if ($this->evento) {
            $this->horaInicio         = $this->trimHi($this->evento->hora_inicio);
            $this->horaFin            = $this->trimHi($this->evento->hora_fin);
            $this->horaInicioInvitado = $this->trimHi($this->evento->hora_inicio_invitado);
            $this->horaFinInvitado    = $this->trimHi($this->evento->hora_fin_invitado);
            $this->fechaAtencion      = $this->evento->fecha ?? null; // línea (1ª, lectura)
            $this->fechaEnvio         = $this->evento->fecha ?? null; // presencial (1ª, input sugerido)
        }
    }

    private function cargarTipoProceso(): void
    {
        $this->tipoProcesoId = Solicitud::whereKey($this->solicitudId)->value('tipo_proceso_id');
        if ($this->tipoProcesoId !== null) {
            $this->tipoProcesoId = (int) $this->tipoProcesoId;
        }
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

    private function cargarInvitaciones(): void
    {
        $this->invitaciones = Invitacion::where('solicitud_id', $this->solicitudId)
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->get();
    }

    private function ultimaInvitacion(): ?Invitacion
    {
        return Invitacion::where('solicitud_id', $this->solicitudId)
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

    /** Decide si hay que mostrar la sección de aceptación */
    private function sincronizarEstadoUI(): void
    {
        $ultima = $this->ultimaInvitacion();
        $this->mostrarPreguntaAceptacion =
            (bool)($ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso));
        if (!$this->mostrarPreguntaAceptacion) {
            $this->aceptoProceso = null;
        }
    }

    private function formatoHorario(?string $inicio, ?string $fin): string
    {
        if (!$inicio || !$fin) return '—';
        return Carbon::parse($inicio)->format('H:i') . ' - ' . Carbon::parse($fin)->format('H:i');
    }

    /**
     * Guardar invitación (1ª o N-ésima) con reglas:
     * - Si la última no tiene asistencia -> bloquear.
     * - Si next >= 2 y última asistió:
     *     - Si falta aceptar/rechazar -> bloquear.
     *     - Si aceptó -> bloquear definitivamente.
     *     - Si NO aceptó -> permitir.
     * - Si tipo_proceso_id = 1 (Pre-mediación) -> máximo 2 invitaciones.
     */
    public function store(string $context = 'primera'): void
    {
        $ultima = $this->ultimaInvitacion();

        // 1) Si existe última sin asistencia registrada -> bloquear
        if ($ultima && is_null($ultima->asistio)) {
            Toaster::warning("Registra la asistencia de la invitación #{$ultima->numero_inv} antes de crear una nueva.");
            return;
        }

        // 2) Consecutivo a crear
        $next = (int)(Invitacion::where('solicitud_id', $this->solicitudId)->max('numero_inv') ?? 0) + 1;

        // 3) Límite por tipo de proceso (refrescar tipo actual por si cambió)
        $this->cargarTipoProceso();
        if ((int)$this->tipoProcesoId === 1 && $next > 2) {
            Toaster::warning('En Pre-mediación solo se permiten 2 invitaciones.');
            return;
        }

        // 4) Reglas para 2ª y subsecuentes
        if ($next >= 2 && $ultima) {
            if ($ultima->asistio === 1) {
                if (is_null($ultima->acepta_proceso)) {
                    Toaster::warning('Confirma si aceptaron mediación antes de crear otra invitación.');
                    return;
                }
                if ((int)$ultima->acepta_proceso === 1) {
                    Toaster::warning('Ya aceptaron mediación. No se permiten nuevas invitaciones.');
                    return;
                }
                // acepta_proceso === 0 -> permitir
            }
            // asistio === 0 -> permitir
        }

        // 5) Validación dinámica por modalidad/contexto
        $rules = [];
        if ($this->modalidad === 'linea') {
            if ($context === 'nueva') {
                $rules['urlNuevaInv']   = ['required', 'url'];
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['enlaceReunion'] = ['required', 'url']; // 1ª: solo URL
            }
        } else {
            // Presencial
            if ($context === 'nueva') {
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['fechaEnvio']    = ['required', 'date']; // 1ª presencial: fecha_envio
            }
        }
        $this->validate($rules);

        $solicitud = Solicitud::find($this->solicitudId);
        if (!$solicitud) { Toaster::error('No se encontró la solicitud.'); return; }

        // Inputs según modalidad/contexto
        $url = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->urlNuevaInv : $this->enlaceReunion)
            : null;

        $fechaAtencion = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : ($this->evento->fecha ?? null))
            : null;

        $fechaEnvio = $this->modalidad !== 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : $this->fechaEnvio)
            : null;

        // Consecutivo siguiente
        $siguienteNumero = $next;

        // FK segura
        $facilitadorId = $solicitud->facilitador_id;
        if ($facilitadorId && !User::whereKey($facilitadorId)->exists()) {
            $facilitadorId = null;
        }

        // Horarios
        if ($this->modalidad === 'linea') {
            $horaInicio          = $this->horaInicio          ?? $this->trimHi($this->evento->hora_inicio ?? null);
            $horaFin             = $this->horaFin             ?? $this->trimHi($this->evento->hora_fin ?? null);
            $esSeparados         = (int)($this->evento->opcion_invitacion ?? 1) === 0;
            $horaInicioInvitado  = $esSeparados ? ($this->horaInicioInvitado ?? $this->trimHi($this->evento->hora_inicio_invitado ?? null)) : null;
            $horaFinInvitado     = $esSeparados ? ($this->horaFinInvitado    ?? $this->trimHi($this->evento->hora_fin_invitado    ?? null)) : null;
        } else {
            $horaInicio = $horaFin = $horaInicioInvitado = $horaFinInvitado = null;
        }

        // Crear invitación (estatus fijo 5; acepta_proceso siempre null al crear)
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
            'numero_inv'           => $siguienteNumero,
            'asistio'              => null,
            'tipo_proceso_id'      => null ?? $solicitud->tipo_proceso_id,
            'estatus_id'           => 5,
            'acudiran_juntos'      => (bool) ($this->evento->opcion_invitacion ?? true),
            'acepta_proceso'       => null,
        ]);

        if ($siguienteNumero === 1) {
            $solicitud->update(['tipo_proceso_id' => 1]);
            $this->tipoProcesoId = 1; // refrescar en el componente
        }

        // Correos (solo en línea con URL)
        if ($this->modalidad === 'linea' && $url) {
            $horarioSolic = $this->formatoHorario($horaInicio, $horaFin);
            $horarioInv   = $this->formatoHorario($horaInicioInvitado, $horaFinInvitado);
            $fechaTxt     = $fechaAtencion ?: ($this->evento->fecha ?? '—');

            foreach ($this->correosSolicitantes as $correo) {
                Mail::to($correo)->send(new InvitacionMediacion('Solicitante', $url, $horarioSolic, $fechaTxt));
            }
            $horarioParaInv = ((int)($this->evento->opcion_invitacion ?? 1) === 0) ? $horarioInv : $horarioSolic;
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

        Toaster::success("Invitación #{$siguienteNumero} guardada.");
    }
    
    /** Registrar (o cambiar) asistencia en una invitación */
    public function marcarAsistencia(int $invitacionId, bool $valor): void
    {
        $inv = Invitacion::where('solicitud_id', $this->solicitudId)
            ->whereKey($invitacionId)
            ->first();

        if (!$inv) { Toaster::error('Invitación no encontrada.'); return; }

        // Si cambia asistencia, limpiar acepta_proceso de ESA invitación
        $inv->update([
            'asistio'        => $valor,
            'acepta_proceso' => null,
        ]);

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();
        Toaster::success('Asistencia actualizada.');
    }

    /** Confirmar (radio) aceptación SÍ/NO para la ÚLTIMA invitación asistida */
    public function confirmarAceptacionProceso(): void
    {
        $ultima = $this->ultimaInvitacion();

        if (!$ultima) {
            Toaster::error('No hay invitaciones registradas.');
            return;
        }

        if ($ultima->asistio !== 1) {
            Toaster::error('Solo puedes confirmar si aceptó mediación cuando la última invitación tuvo asistencia.');
            return;
        }

        if ($this->aceptoProceso === null) {
            Toaster::error('Selecciona si “Aceptó mediación” (Sí/No).');
            return;
        }

        $ultima->update([
            'acepta_proceso' => $this->aceptoProceso,
        ]);

        $this->cargarInvitaciones();
        $this->sincronizarEstadoUI();

        // Feedback
        if ($this->aceptoProceso) {
            Toaster::success('Registrado: Aceptó mediación. No se permitirán nuevas invitaciones.');
        } else {
            Toaster::success('Registrado: No aceptó mediación. Puedes crear una nueva invitación.');
        }

        $this->aceptoProceso = null;
    }

    public function render()
    {
        return view('livewire.solicitud.invitaciones-panel');
    }
}
