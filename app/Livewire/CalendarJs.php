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
    public  $observacion = '';

    public $solicitud = '';
    public $actividad = '';
    public $facilitador = '';

    public  $horaInicio = '';
    public  $horaFin = '';
    public  $horaInicioInvitado = '';
    public  $horaFinInvitado = '';

    public  $colorEvento = '';
    public  $fechaNueva = '';
    public  $reasignacion = 2; // 1=Sí, 2=No
    public  $acudiran_juntos = ''; // se define al elegir solicitud o al editar

    public function mount()
    {
        $now = Carbon::now();
        $this->currentMonth = (int) $now->month;
        $this->currentYear  = (int) $now->year;
        $this->fechaSeleccionada = $now->toDateString();

        $this->rolUsuario = auth()->user()->getRoleNames()->first() ?? 'user';

        // Cache 10 min para lista estática
        $this->facilitadores = Cache::remember('facilitadores_list_v1', 600, function () {
            return Facilitador::select('id', 'nombre')->get();
        });

        // Si prefieres, carga solicitudes sólo al abrir modal (ver abrirModalDia)
        $this->cargarSolicitudes();

        $this->horarios = $this->generarHorarios('09:00', '19:00');

        $this->cargarEventosYDias();
    }

    public function cargarSolicitudes()
    {
        $this->solicitudes = Solicitud::select('id', 'folio_materia', 'materia', 'estatus_id', 'facilitador_id')
            ->whereNull('facilitador_id')
            ->where('estatus_id', 1)
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
        // Sólo columnas necesarias y relaciones acotadas
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

        // Días con eventos desde SQL (más rápido)
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

        // Trae una sola vez lo necesario de la solicitud
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
            'materia'              => $this->rolUsuario,
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
        $sol = Solicitud::select('materia', 'acudiran_juntos')->findOrFail($this->solicitud);

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
        ]);

        Solicitud::whereKey($this->solicitud)->update([
            'estatus_id'     => 3,
            'facilitador_id' => $this->facilitador,
        ]);
    }

    private function crearNuevoEvento()
    {
        $sol = Solicitud::select('materia', 'acudiran_juntos')->findOrFail($this->solicitud);

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
            'activo'               => 1,
        ]);

        Solicitud::whereKey($this->solicitud)->update([
            'estatus_id'     => 2,
            'facilitador_id' => $this->facilitador,
        ]);

        $this->reasignarFacilitadores();
    }

    public function editarEvento($id)
    {
        $this->modoEditar = true;

        $evento = Agenda::findOrFail($id);

        $this->eventoId          = $evento->id;
        $this->fechaSeleccionada = $evento->fecha;
        $this->fechaNueva        = $evento->fecha;

        $this->facilitador = (int) $evento->facilitador_id;
        $this->solicitud   = (int) $evento->solicitud_id;

        // Si en DB están como 'H:i:s', tomamos 'H:i' sin Carbon
        $this->horaInicio        = $this->hi($evento->hora_inicio);
        $this->horaFin           = $this->hi($evento->hora_fin);
        $this->horaInicioInvitado= $this->hi($evento->hora_inicio_invitado);
        $this->horaFinInvitado   = $this->hi($evento->hora_fin_invitado);

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

    public function abrirModalDia()
    {
        $this->resetValidation();

        // Cargar solicitudes sólo al abrir para evitar golpe inicial si prefieres
        $this->cargarSolicitudes();

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
}
