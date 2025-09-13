<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Agenda;
use App\Models\Solicitud;
use App\Models\Facilitador;
use App\Models\Solicitante;
use Illuminate\Support\Facades\Cache;

class CalendarJs extends Component
{
    public string $fechaSeleccionada;
    public int $currentMonth;
    public int $currentYear;

    /** @var \Illuminate\Support\Collection */
    public $eventos;
    public array $diasConEventos = [];

    public bool $modoEditar = false;
    public bool $showModalDia = false;
    public string $rolUsuario;

    public array $horarios = [];
    /** @var \Illuminate\Support\Collection */
    public $facilitadores;
    /** @var \Illuminate\Support\Collection */
    public $solicitudes;

    public $eventoId = '';
    public $observacion = '';

    public $solicitud = '';
    public $actividad = '';
    public $facilitador = '';

    public $horaInicio = '';
    public $horaFin = '';
    public $horaInicioInvitado = '';
    public $horaFinInvitado = '';

    public $colorEvento = '';
    public $fechaNueva = '';
    public $reasignacion = 2; // 1=Sí, 2=No
    public $acudiran_juntos = ''; // se define al elegir solicitud o al editar

    /**
     * Recibe {solicitudID} desde la ruta: /calendario/{solicitudID}
     */
    public function mount(?int $solicitudID = null)
    {
        $now = Carbon::now();
        $this->currentMonth = (int) $now->month;
        $this->currentYear  = (int) $now->year;
        $this->fechaSeleccionada = $now->toDateString();

        $this->rolUsuario = auth()->user()->getRoleNames()->first() ?? 'user';

        // Cache 10 min para lista estática de facilitadores
        $this->facilitadores = Cache::remember('facilitadores_list_v1', 600, function () {
            return Facilitador::select('id', 'nombre')->get();
        });

        // Lista base de solicitudes
        $this->cargarSolicitudes();

        // Horarios y eventos del mes actual
        $this->horarios = $this->generarHorarios('09:00', '19:00');
        $this->cargarEventosYDias();

        // Abrir en modo edición con el evento más reciente/activo de esa solicitud
        if ($solicitudID) {
            if ($evento = $this->eventoParaEdicionPorSolicitud($solicitudID)) {
                $this->editarEvento($evento->id);
            } else {
                // Fallback: si no hay evento, abrir modal de creación con la solicitud precargada
                $this->abrirModalDia($solicitudID);
            }
        }
    }

    /**
     * Carga solicitudes que cumplan los filtros.
     * Si $includeId viene y no cumple filtros, se inyecta para que aparezca en el select.
     */
   public function cargarSolicitudes(?int $includeId = null)
    {
        $base = Solicitud::select('id', 'folio_materia', 'materia', 'estatus_id', 'facilitador_id', 'tipo_proceso_id')
            ->whereNull('facilitador_id')
            ->where(function ($q) {
                $q->where('estatus_id', 1)
                ->orWhere(function ($q2) {
                    $q2->where('estatus_id', 2)
                        ->where('tipo_proceso_id', 2);
                });
            })
            ->when($this->rolUsuario !== 'admin', function ($query) {
                $query->where(function ($q) {
                    if ($this->rolUsuario === 'civil') {
                        $q->whereIn('materia', ['civil', 'mercantil']);
                    } else {
                        $q->where('materia', $this->rolUsuario);
                    }
                });
            })
            ->get();

        if ($includeId && !$base->contains('id', $includeId)) {
            if ($extra = Solicitud::select('id','folio_materia','materia','estatus_id','facilitador_id','tipo_proceso_id')
                                ->find($includeId)) {
                $base->push($extra);
            }
        }

        $this->solicitudes = $base;
    }


    public function cambiarMes($incremento)
    {
        $nuevoMes = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonths($incremento);
        $this->currentMonth = (int) $nuevoMes->month;
        $this->currentYear  = (int) $nuevoMes->year;
        $this->cargarEventosYDias();
    }

    public function seleccionarDia($dia)
    {
        $this->fechaSeleccionada = Carbon::create($this->currentYear, $this->currentMonth, (int) $dia)->toDateString();
        $this->cargarEventos();
    }

    public function cargarEventos()
    {
        $this->eventos = Agenda::select(
                'id','fecha',
                'hora_inicio','hora_fin',
                'hora_inicio_invitado','hora_fin_invitado',
                'color','solicitud_id','facilitador_id',
                'estatus_id','opcion_invitacion'
            )
            ->with([
                'facilitador:id,nombre',
                'solicitud:id,folio_materia',
                'estatus:id,nombre',
            ])
            ->whereDate('fecha', $this->fechaSeleccionada)
            ->where('activo', 1)
            ->get();
    }

    public function cargarEventosYDias()
    {
        $fecha = Carbon::create($this->currentYear, $this->currentMonth, 1);

        $this->diasConEventos = Agenda::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $fecha->year)
            ->where('activo', 1)
            ->selectRaw('DAY(fecha) as d')
            ->groupBy('d')
            ->pluck('d')
            ->toArray();

        $this->cargarEventos();
    }

    // ---------------- CREAR / ACTUALIZAR ----------------

    public function guardarEvento()
    {
        $this->validateDatos();

        if ($this->eventoId) {
            $this->procesarActualizacion();
        } else {
            $this->crearNuevoEvento();
        }

        $this->cargarEventosYDias();
        $this->cargarSolicitudes();
        $this->cerrar();
    }

    private function validateDatos()
    {
        $rules = [
            'solicitud'   => 'required|exists:solicitudes,id',
            'facilitador' => 'required|exists:facilitadores,id',
            'horaInicio'  => 'required',
            'horaFin'     => 'required|after:horaInicio',
            'actividad'   => 'required|string|max:255',
        ];

        if ($this->acudiran_juntos === false) {
            $rules['horaInicioInvitado'] = 'required';
            $rules['horaFinInvitado']    = 'required|after:horaInicioInvitado';
        }

        $this->validate($rules);
    }

    private function procesarActualizacion()
    {
        $evento = Agenda::find($this->eventoId);
        if (!$evento) return;

        $sol = Solicitud::select('materia', 'acudiran_juntos', 'facilitador_id')->findOrFail($this->solicitud);
        $acudiran_juntos = (bool) $sol->acudiran_juntos;

        if ($this->reasignacion == 1) {
            $this->desactivarEventosAnteriores();
            $this->crearReasignacion();
            return;
        }

        $cambioFacilitador = (int) $evento->facilitador_id !== (int) $this->facilitador;

        $evento->update([
            'solicitud_id'         => $this->solicitud,
            'opcion_invitacion'    => $acudiran_juntos,
            'facilitador_id'       => $this->facilitador,
            'fecha'                => $this->fechaNueva ?? $this->fechaSeleccionada,
            'hora_inicio'          => $this->horaInicio,
            'hora_fin'             => $this->horaFin,
            'hora_inicio_invitado' => $this->horaInicioInvitado ?: null,
            'hora_fin_invitado'    => $this->horaFinInvitado ?: null,
            'descripcion'          => $this->actividad,
            'materia'              => $sol->materia,
            'color'                => $this->colorEvento,
        ]);

        if ($cambioFacilitador) {
            $sol->update(['facilitador_id' => $this->facilitador]);
        }

        $this->reasignarFacilitadores();
    }

    private function desactivarEventosAnteriores()
    {
        Agenda::where('solicitud_id', $this->solicitud)
            ->whereIn('estatus_id', [2, 4])
            ->update([
                'estatus_id' => 5, // Ocultos
                'activo'     => 0,
            ]);
    }

    private function crearReasignacion()
    {
        $sol = Solicitud::select('materia', 'acudiran_juntos', 'tipo_proceso_id')->findOrFail($this->solicitud);

        Agenda::create([
            'solicitud_id'         => $this->solicitud,
            'opcion_invitacion'    => (bool) $sol->acudiran_juntos,
            'facilitador_id'       => $this->facilitador,
            'fecha'                => $this->fechaNueva ?? $this->fechaSeleccionada,
            'hora_inicio'          => $this->horaInicio,
            'hora_fin'             => $this->horaFin,
            'hora_inicio_invitado' => $this->horaInicioInvitado ?: null,
            'hora_fin_invitado'    => $this->horaFinInvitado ?: null,
            'descripcion'          => $this->actividad,
            'materia'              => $sol->materia,
            'color'                => $this->colorEvento,
            'observacion'          => $this->observacion,
            'estatus_id'           => 3, // Re-asignado
            'activo'               => 1,
            'tipo_proceso_id'      => $sol->tipo_proceso_id ?: null,
        ]);

        Solicitud::whereKey($this->solicitud)->update([
            'estatus_id'     => 3,
            'facilitador_id' => $this->facilitador,
        ]);
    }

    private function crearNuevoEvento()
    {
        $sol = Solicitud::select('materia', 'acudiran_juntos', 'tipo_proceso_id')->findOrFail($this->solicitud);

        Agenda::create([
            'solicitud_id'         => $this->solicitud,
            'facilitador_id'       => $this->facilitador,
            'opcion_invitacion'    => (bool) $sol->acudiran_juntos,
            'fecha'                => $this->fechaSeleccionada,
            'hora_inicio'          => $this->horaInicio,
            'hora_fin'             => $this->horaFin,
            'hora_inicio_invitado' => $this->horaInicioInvitado ?: null,
            'hora_fin_invitado'    => $this->horaFinInvitado ?: null,
            'descripcion'          => $this->actividad,
            'materia'              => $sol->materia,
            'color'                => $this->colorEvento,
            'observacion'          => $this->observacion,
            'estatus_id'           => 2, // Asignado
            'tipo_proceso_id'      => 1, // pre-mediacion
            'activo'               => 1,
            'tipo_proceso_id'      => $sol->tipo_proceso_id ?: null,
        ]);

        Solicitud::whereKey($this->solicitud)->update([
            'estatus_id'     => 2,
            'facilitador_id' => $this->facilitador,
            'tipo_proceso_id' => $sol->tipo_proceso_id ?? 1,
        ]);

        $this->reasignarFacilitadores();
    }

    public function editarEvento($id)
    {
        $this->modoEditar = true;

        $evento = Agenda::findOrFail($id);

        // Garantiza que la solicitud del evento aparezca en el select aunque no cumpla filtros
        $this->cargarSolicitudes((int) $evento->solicitud_id);

        $this->eventoId          = $evento->id;
        $this->fechaSeleccionada = $evento->fecha;
        $this->fechaNueva        = $evento->fecha;

        $this->facilitador = (int) $evento->facilitador_id;
        $this->solicitud   = (int) $evento->solicitud_id;

        // Si en DB están como 'H:i:s', tomamos 'H:i'
        $this->horaInicio         = $this->hi($evento->hora_inicio);
        $this->horaFin            = $this->hi($evento->hora_fin);
        $this->horaInicioInvitado = $this->hi($evento->hora_inicio_invitado);
        $this->horaFinInvitado    = $this->hi($evento->hora_fin_invitado);

        $this->actividad   = (string) $evento->descripcion;
        $this->colorEvento = (string) $evento->color;
        $this->observacion = (string) ($evento->observacion ?? '');

        $this->acudiran_juntos = !is_null($evento->opcion_invitacion)
            ? (bool) $evento->opcion_invitacion
            : (bool) Solicitud::whereKey($evento->solicitud_id)->value('acudiran_juntos');

        $this->showModalDia = true;
    }

    private function reasignarFacilitadores()
    {
        if ($this->reasignacion === 1) {
            Solicitante::where('solicitud_id', $this->solicitud)->update([
                'facilitador_id' => null,
                'estatus_id'     => null,
            ]);
        }

        // Asignación básica para todos los solicitantes de la solicitud
        Solicitante::where('solicitud_id', $this->solicitud)->update([
            'facilitador_id' => $this->facilitador,
            'estatus_id'     => 3,
        ]);
    }

    // ---------------- REACTIVIDAD ----------------

    public function updatedSolicitud($value)
    {
        $v = Solicitud::whereKey($value)->value('acudiran_juntos');
        $this->acudiran_juntos = is_null($v) ? null : (bool) $v;

        if ($this->acudiran_juntos === true) {
            $this->horaInicioInvitado = null;
            $this->horaFinInvitado    = null;
        }
    }

    // ---------------- HELPERS ----------------

    private function generarHorarios(string $inicio, string $fin): array
    {
        $horarios = [];
        $hora    = Carbon::createFromFormat('H:i', $inicio);
        $horaFin = Carbon::createFromFormat('H:i', $fin);

        while ($hora < $horaFin) {
            $horarios[$hora->format('H:i')] = $hora->format('g:i A');
            $hora->addMinutes(30);
        }

        return $horarios;
    }

    private function hi(?string $time): ?string
    {
        if (!$time) return null;
        // asume 'H:i:s' o 'H:i'
        return substr($time, 0, 5);
    }

    /**
     * Abrir modal de creación. Puede recibir una solicitud para precargarla.
     */
    public function abrirModalDia(?int $solicitudID = null)
    {
        $this->resetValidation();

        // Trae la lista e incluye la solicitud pedida (si aplica)
        $this->cargarSolicitudes($solicitudID);

        $this->reset([
            'eventoId',
            'facilitador',
            'horaInicio', 'horaFin',
            'horaInicioInvitado', 'horaFinInvitado',
            'actividad',
            'colorEvento',
            'observacion',
            'fechaNueva',
            'reasignacion',
        ]);

        if ($solicitudID) {
            $this->solicitud = $solicitudID;

            // Pre-cargar acudiran_juntos
            $v = Solicitud::whereKey($solicitudID)->value('acudiran_juntos');
            $this->acudiran_juntos = is_null($v) ? null : (bool) $v;

            if ($this->acudiran_juntos === true) {
                $this->horaInicioInvitado = null;
                $this->horaFinInvitado    = null;
            }
        } else {
            $this->acudiran_juntos = null;
        }

        $this->modoEditar = false;
        $this->showModalDia = true;
    }

    public function cerrar()
    {
        $this->showModalDia = false;
        $this->modoEditar   = false;

        $this->resetValidation();

        $this->reset([
            'eventoId',
            'solicitud',
            'facilitador',
            'horaInicio', 'horaFin',
            'horaInicioInvitado', 'horaFinInvitado',
            'actividad',
            'colorEvento',
            'observacion',
            'fechaNueva',
            'reasignacion',
        ]);

        $this->acudiran_juntos = null;
    }

    public function render()
    {
        $diasEnMes    = Carbon::create($this->currentYear, $this->currentMonth)->daysInMonth;
        $primerDiaMes = Carbon::create($this->currentYear, $this->currentMonth, 1)->dayOfWeekIso;

        return view('livewire.calendar-js', [
            'diasEnMes'    => $diasEnMes,
            'primerDiaMes' => $primerDiaMes,
            'currentMonth' => $this->currentMonth,
            'currentYear'  => $this->currentYear,
        ]);
    }

    /**
     * Devuelve el evento preferible para editar por solicitud:
     * 1) activo y estatus asignado/reasignado (2,3), más reciente
     * 2) si no hay, el más reciente que exista
     */
    private function eventoParaEdicionPorSolicitud(int $solicitudID): ?Agenda
    {
        $preferido = Agenda::query()
            ->where('solicitud_id', $solicitudID)
            ->where('activo', 1)
            ->whereIn('estatus_id', [2, 3]) // 2=Asignado, 3=Re-asignado (ajusta a tu catálogo)
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->first();

        if ($preferido) {
            return $preferido;
        }

        return Agenda::query()
            ->where('solicitud_id', $solicitudID)
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->first();
    }
}
