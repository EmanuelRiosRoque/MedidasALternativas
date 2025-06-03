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
    public $motivo_reasignacion = '';

    public $solicitud = '';
    public $actividad = '';
    public $facilitador = '';
    public $horaInicio = '';
    public $horaFin = '';
    public $colorEvento = '';
    public $solicitantes = [];
    public $solicitante = '';


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
            $query->whereNull('facilitador')
                ->orWhere('facilitador', '');
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
        $this->validate([
            'solicitud'   => 'required|exists:solicitudes,id',
            'facilitador' => 'required|exists:facilitadores,id',
            'horaInicio'  => 'required',
            'horaFin'     => 'required|after:horaInicio',
            'actividad'   => 'required|string|max:255',
        ]);

        if ($this->eventoId) {
            // Actualizar evento existente
            $evento = Agenda::find($this->eventoId);

            if ($evento) {
                $cambioFacilitador = $evento->facilitador_id != $this->facilitador;

                $evento->update([
                    'facilitador_id' => $this->facilitador,
                    'fecha'          => $this->fechaSeleccionada,
                    'hora_inicio'    => $this->horaInicio,
                    'hora_fin'       => $this->horaFin,
                    'descripcion'    => $this->actividad,
                    'materia'        => $this->rolUsuario,
                    'color'          => $this->colorEvento,
                    'motivo_reasignacion' => $this->motivo_reasignacion,
                ]);

                // if ($cambioFacilitador) {
                    // Limpiar todos los facilitadores de la solicitud antes de reasignar
                    Solicitante::where('solicitud_id', $this->solicitud)
                        ->update([
                            'facilitador_id' => null,
                            'estatus_id'     => null,
                        ]);

                    // Luego aplicar la asignación según el tipo seleccionado
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
                // }

                }
        } else {
            // Crear nuevo evento
            Agenda::create([
                'solicitud_id'   => $this->solicitud,
                'facilitador_id' => $this->facilitador,
                'fecha'          => $this->fechaSeleccionada,
                'hora_inicio'    => $this->horaInicio,
                'hora_fin'       => $this->horaFin,
                'descripcion'    => $this->actividad,
                'materia'        => $this->rolUsuario,
                'color'          => $this->colorEvento,
                'motivo_reasignacion' => $this->motivo_reasignacion,
            ]);

            // Asignar a solicitantes según selección
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

        $this->cargarEventosYDias();
        $this->cerrar();
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
        $this->horaInicio = $evento->hora_inicio;
        $this->horaFin = $evento->hora_fin;
        $this->actividad = $evento->descripcion;
        $this->colorEvento = $evento->color;
        $this->colorEvento = $evento->color;
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
