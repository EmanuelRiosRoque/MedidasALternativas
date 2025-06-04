<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Agenda;
use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Facilitador;
use App\Models\Solicitante;

class CalendarJs extends Component
{
    public $fechaSeleccionada;
    public $currentMonth;
    public $currentYear;
    public $eventos = [];
    public $diasConEventos = [];



    public bool $modoEditar = false;
    public bool $showModalDia = false;
    public $rolUsuario;


    public $horarios = [];
    public $facilitadores = [];
    public $solicitudes = [];
    public $eventoId = null;
    public $observacion = '';

    public $solicitud = '';
    public $actividad = '';
    public $facilitador = '';
    public $horaInicio = '';
    public $horaFin = '';
    public $colorEvento = '';
    public $solicitantes = [];
    public $solicitante = '';
    public $fechaSeleccionadaUpdate;
    public $reasignacion = 2;

    public function mount()
    {
        $now = Carbon::now();
        $this->currentMonth = $now->month;
        $this->currentYear = $now->year;
        $this->fechaSeleccionada = $now->toDateString();
        $this->cargarEventosYDias();

        $this->facilitadores = Facilitador::all();
        $this->rolUsuario = auth()->user()->getRoleNames()->first();


        $this->solicitudes = Solicitud::where(function ($query) {
            $query->whereNull('facilitador_id')
                ->orWhere('facilitador_id', '');
        })
            ->where('estatus_id', 1)
            ->where(function ($query) {
                if ($this->rolUsuario === 'civil') {
                    $query->whereIn('materia', ['civil', 'mercantil']);
                } else {
                    $query->where('materia', $this->rolUsuario);
                }
            })
            ->get();

        $this->horarios = $this->generarHorarios('09:00', '19:00');
    }

    public function cambiarMes($incremento)
    {
        $nuevoMes = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonths($incremento);
        $this->currentMonth = $nuevoMes->month;
        $this->currentYear = $nuevoMes->year;
        $this->cargarEventosYDias();
    }

    public function seleccionarDia($dia)
    {
        $this->fechaSeleccionada = Carbon::create($this->currentYear, $this->currentMonth, $dia)->toDateString();
        $this->cargarEventos();
    }

    public function cargarEventos()
    {
        $this->eventos = Agenda::with('facilitador', 'solicitud') // si tienes relación definida
            ->whereDate('fecha', $this->fechaSeleccionada)
            ->where('activo', 1)
            ->get();
    }

    public function cargarEventosYDias()
    {
        $fecha = Carbon::create($this->currentYear, $this->currentMonth, 1);

        $this->diasConEventos = Agenda::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $fecha->year)
            ->pluck('fecha')
            ->map(fn($f) => Carbon::parse($f)->day)
            ->toArray();

        $this->cargarEventos();
    }

    public function guardarEvento()
    {
        // $folioFamiliar = siguienteValorSecuencia('familiar'); // 1
        // $folioCivil = siguienteValorSecuencia('civil');       // 1

        // $folioFamiliarFormateado = 'FAM-' . now()->year . '-' . str_pad($folioFamiliar, 4, '0', STR_PAD_LEFT);
        // $folioCivilFormateado = 'CIV-' . now()->year . '-' . str_pad($folioCivil, 4, '0', STR_PAD_LEFT);

        // dd($folioFamiliarFormateado, $folioCivilFormateado);

        

        $this->validateDatos();

        if ($this->eventoId) {
            $this->procesarActualizacion();
        } else {
            $this->crearNuevoEvento();
        }

        $this->cargarEventosYDias();
        $this->cerrar();
    }

    private function validateDatos()
    {
        $this->validate([
            'solicitud'   => 'required|exists:solicitudes,id',
            'facilitador' => 'required|exists:facilitadores,id',
            'horaInicio'  => 'required',
            'horaFin'     => 'required|after:horaInicio',
            'actividad'   => 'required|string|max:255',
        ]);
    }

    private function procesarActualizacion()
    {
        $evento = Agenda::find($this->eventoId);

        if (!$evento) return;

        if ($this->reasignacion == 1) {
            $this->desactivarEventosAnteriores();
            $this->crearReasignacion();
        } else {
            $cambioFacilitador = $evento->facilitador_id != $this->facilitador;

            $evento->update([
                'solicitud_id'      => $this->solicitud,
                'opcion_invitacion' => $this->solicitante,
                'facilitador_id'    => $this->facilitador,
                'fecha'             => $this->fechaSeleccionadaUpdate ?? $this->fechaSeleccionada,
                'hora_inicio'       => $this->horaInicio,
                'hora_fin'          => $this->horaFin,
                'descripcion'       => $this->actividad,
                'materia'           => $this->rolUsuario,
                'color'             => $this->colorEvento,
            ]);

            if ($cambioFacilitador) {
                Solicitud::where('id', $this->solicitud)
                    ->update([
                        'facilitador_id' => $this->facilitador,
                    ]);
            }
        }


        $this->reasignarFacilitadores();
    }

    private function desactivarEventosAnteriores()
    {
        Agenda::where('solicitud_id', $this->solicitud)
            ->whereIn('estatus_id', [2, 4])
            ->update([
                'estatus_id' => 5,
                'activo'     => 0
            ]);
    }

    private function crearReasignacion()
    {
        Agenda::create([
            'solicitud_id'      => $this->solicitud,
            'opcion_invitacion' => $this->solicitante,
            'facilitador_id'    => $this->facilitador,
            'fecha'             => $this->fechaSeleccionadaUpdate ?? $this->fechaSeleccionada,
            'hora_inicio'       => $this->horaInicio,
            'hora_fin'          => $this->horaFin,
            'descripcion'       => $this->actividad,
            'materia'           => $this->rolUsuario,
            'color'             => $this->colorEvento,
            'observacion'       => $this->observacion,
            'estatus_id'        => 4,
            'activo'            => 1,
        ]);

        Solicitud::where('id', $this->solicitud)
        ->update([
            'estatus_id'     => 4,
            'facilitador_id' => $this->facilitador,
        ]);

    }

    private function crearNuevoEvento()
    {
        Agenda::create([
            'solicitud_id'      => $this->solicitud,
            'facilitador_id'    => $this->facilitador,
            'opcion_invitacion' => $this->solicitante,
            'fecha'             => $this->fechaSeleccionada,
            'hora_inicio'       => $this->horaInicio,
            'hora_fin'          => $this->horaFin,
            'descripcion'       => $this->actividad,
            'materia'           => $this->rolUsuario,
            'color'             => $this->colorEvento,
            'observacion'       => $this->observacion,
            'estatus_id'        => 2,
            'activo'            => 1,
        ]);
        Solicitud::where('id', $this->solicitud)
           ->update([
            'estatus_id'     => 2,
            'facilitador_id' => $this->facilitador,
        ]);

        $this->reasignarFacilitadores();
    }

    private function reasignarFacilitadores()
    {
        // Limpiar asignaciones previas
        Solicitante::where('solicitud_id', $this->solicitud)
            ->update([
                'facilitador_id' => null,
                'estatus_id'     => null,
            ]);

        // Asignar según tipo
        switch ($this->solicitante) {
            case 'todos':
                Solicitante::where('solicitud_id', $this->solicitud)
                    ->update([
                        'facilitador_id' => $this->facilitador,
                        'estatus_id'     => 3,
                    ]);
                break;

            case 'solicitantes':
                Solicitante::where('solicitud_id', $this->solicitud)
                    ->where('tipo_solicitante', 'solicitante')
                    ->update([
                        'facilitador_id' => $this->facilitador,
                        'estatus_id'     => 3,
                    ]);
                break;

            case 'invitados':
                Solicitante::where('solicitud_id', $this->solicitud)
                    ->where('tipo_solicitante', 'invitado')
                    ->update([
                        'facilitador_id' => $this->facilitador,
                        'estatus_id'     => 3,
                    ]);
                break;

            default:
                if (is_numeric($this->solicitante)) {
                    Solicitante::where('id', $this->solicitante)
                        ->update([
                            'facilitador_id' => $this->facilitador,
                            'estatus_id'     => 3,
                        ]);
                }
                break;
        }
    }

    public function abrirModalDia()
    {
        $this->showModalDia = true;
    }

    public function cerrar()
    {
        $this->showModalDia = false;
        $this->modoEditar = false;

        $this->resetValidation(); // Limpia los errores de validación

        // Opcional: limpia los campos del formulario si quieres reiniciar todo
        $this->reset([
            'solicitud',
            'facilitador',
            'horaInicio',
            'horaFin',
            'actividad',
            'fechaSeleccionada',
            'solicitante',
            'solicitantes',
            'observacion',
            'reasignacion'
        ]);
    }

    public function editarEvento($id)
    {
        $this->modoEditar = true;

        $evento = Agenda::findOrFail($id);

        $this->eventoId = $evento->id;
        $this->fechaSeleccionada = $evento->fecha;
        $this->facilitador = $evento->facilitador_id;
        $this->solicitud = $evento->solicitud_id;
        $this->horaInicio = \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i');
        $this->horaFin = \Carbon\Carbon::parse($evento->hora_fin)->format('H:i');       

        $this->actividad = $evento->descripcion;
        $this->colorEvento = $evento->color;
        $this->solicitante = $evento->opcion_invitacion;
        $this->observacion = $evento->observacion;
        $this->solicitantes = Solicitante::where('solicitud_id', $evento->solicitud_id)->get();


        $this->showModalDia = true;
    }


    public function updatedSolicitud($value)
    {
        $this->solicitantes = Solicitante::where('solicitud_id', $value)->get();
    }


    private function generarHorarios($inicio, $fin)
    {
        $horarios = [];
        $hora = \Carbon\Carbon::createFromFormat('H:i', $inicio);
        $horaFin = \Carbon\Carbon::createFromFormat('H:i', $fin);

        while ($hora < $horaFin) {
            $horarios[$hora->format('H:i')] = $hora->format('g:i A');
            $hora->addMinutes(30);
        }

        return $horarios;
    }

    public function render()
    {
        $diasEnMes = Carbon::create($this->currentYear, $this->currentMonth)->daysInMonth;
        $primerDiaMes = Carbon::create($this->currentYear, $this->currentMonth, 1)->dayOfWeekIso;

        return view('livewire.calendar-js', [
            'diasEnMes' => $diasEnMes,
            'primerDiaMes' => $primerDiaMes,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
        ]);
    }
}
