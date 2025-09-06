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
    public ?string $enlaceReunion = null;   // línea: 1ª (solo URL)
    public ?string $urlNuevaInv   = null;   // línea: N-ésima (URL)
    public ?string $fechaAtencion = null;   // línea: 1ª (se toma del evento, lectura)
    public ?string $fechaNuevaInv = null;   // línea: N-ésima (fecha_atencion) | presencial: N-ésima (fecha_envio)
    public ?string $fechaEnvio    = null;   // presencial: 1ª (INPUT)

    // Horarios
    public ?string $horaInicio = null;
    public ?string $horaFin = null;
    public ?string $horaInicioInvitado = null;
    public ?string $horaFinInvitado = null;

    /** Selects de hora */
    public array $horarios = [];

    /** Listado */
    public Collection $invitaciones;

    /** Correos */
    public Collection $correosSolicitantes;
    public Collection $correosInvitados;

    /** Map de estatus */
    public array $estatusLabels = [
        7 => 'Pendiente',
        8 => 'Enviada',
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

        $this->cargarCorreos();
        $this->cargarInvitaciones();

        // Precarga (para mostrar en lectura / sugerir valores)
        if ($this->evento) {
            $this->horaInicio         = $this->trimHi($this->evento->hora_inicio);
            $this->horaFin            = $this->trimHi($this->evento->hora_fin);
            $this->horaInicioInvitado = $this->trimHi($this->evento->hora_inicio_invitado);
            $this->horaFinInvitado    = $this->trimHi($this->evento->hora_fin_invitado);

            // Línea (1ª en lectura): fecha de atención del evento
            $this->fechaAtencion = $this->evento->fecha ?? null;
            // Presencial (1ª): la capturamos, pero prellenamos con la fecha del evento si existe
            $this->fechaEnvio    = $this->evento->fecha ?? null;
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

    private function formatoHorario(?string $inicio, ?string $fin): string
    {
        if (!$inicio || !$fin) return '—';
        return Carbon::parse($inicio)->format('H:i') . ' - ' . Carbon::parse($fin)->format('H:i');
    }

    /**
     * Guardar invitación (1ª o N-ésima).
     * Reglas:
     * - En línea:
     *    - 1ª: SOLO URL; fecha_atencion = fecha del evento (lectura).
     *    - N-ésima: URL + fecha_atencion (fechaNuevaInv).
     * - Presencial:
     *    - 1ª: CAPTURAR fecha_envio (input).
     *    - N-ésima: CAPTURAR fecha_envio (fechaNuevaInv).
     * - Bloqueo: no crear nueva si la última invitación no tiene 'asistio' registrado.
     */
    public function store(string $context = 'primera'): void
    {
        // Bloqueo: si hay última invitación sin asistencia registrada
        $ultima = Invitacion::where('solicitud_id', $this->solicitudId)
            ->orderByDesc('numero_inv')
            ->orderByDesc('id')
            ->first();

        if ($ultima && is_null($ultima->asistio)) {
            Toaster::warning("Registra la asistencia de la invitación #{$ultima->numero_inv} antes de crear una nueva.");
            return;
        }

        // Validación dinámica
        $rules = [];
        if ($this->modalidad === 'linea') {
            if ($context === 'nueva') {
                $rules['urlNuevaInv']   = ['required', 'url'];
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['enlaceReunion'] = ['required', 'url']; // 1ª: solo URL
            }
        } else {
            // PRESENCIAL
            if ($context === 'nueva') {
                $rules['fechaNuevaInv'] = ['required', 'date'];
            } else {
                $rules['fechaEnvio']    = ['required', 'date']; // 1ª presencial SÍ captura fecha_envio
            }
        }
        $this->validate($rules);

        $solicitud = Solicitud::find($this->solicitudId);
        if (!$solicitud) { Toaster::error('No se encontró la solicitud.'); return; }

        // Inputs finales según modalidad/contexto
        $url = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->urlNuevaInv : $this->enlaceReunion)
            : null;

        // En línea:
        $fechaAtencion = $this->modalidad === 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : ($this->evento->fecha ?? null))
            : null;

        // Presencial:
        $fechaEnvio = $this->modalidad !== 'linea'
            ? ($context === 'nueva' ? $this->fechaNuevaInv : $this->fechaEnvio)
            : null;

        // Consecutivo siguiente
        $siguienteNumero = (int)(Invitacion::where('solicitud_id', $this->solicitudId)->max('numero_inv') ?? 0) + 1;

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
            $horaFinInvitado     = $esSeparados ? ($this->horaFinInvitado    ?? $this->trimHi($this->evento->hora_fin_invitado ?? null))    : null;
        } else {
            $horaInicio = $horaFin = $horaInicioInvitado = $horaFinInvitado = null;
        }

        // Crear como PENDIENTE (7)
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
            'asistio'              => null,           // se registrará después
            'tipo_invitacion_id'   => null,
            'estatus_id'           => 7,              // PENDIENTE
            'acudiran_juntos'      => (bool) ($this->evento->opcion_invitacion ?? true),
        ]);

        // Enviar correos si es en línea + URL -> marcar ENVIADA (8)
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

            $inv->update(['estatus_id' => 8]); // ENVIADA
        }

        // Refrescar + limpiar
        $this->cargarInvitaciones();
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

        $inv->update(['asistio' => $valor]);
        $this->cargarInvitaciones();
        Toaster::success('Asistencia actualizada.');
    }

    public function render()
    {
        return view('livewire.solicitud.invitaciones-panel');
    }
}
