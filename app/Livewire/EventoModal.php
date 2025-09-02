<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Agenda;
use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Facilitador;
use Illuminate\Support\Facades\Redirect;

class EventoModal extends Component
{
    public bool $show = false;
    public bool $showModalDia = false;
    public $rolUsuario;

 
    public $horarios = [];
    public $facilitadores = []; 
    public $solicitudes = []; 
    public $fechaSeleccionada = null;
    public $eventoId = null;


    public $solicitud = '';
    public $actividad = '';
    public $facilitador = '';
    public $horaInicio = '';
    public $horaFin = '';
    public $colorEvento = '';

    public function recibirFecha($fecha)
    {
        $this->fechaSeleccionada = $fecha;
        $this->showModalDia = true;
    }

    public function cargarEvento($id)
    {
        $this->eventoId = $id;
        $evento = Agenda::find($id);

        if (!$evento) {
            session()->flash('error', 'Evento no encontrado.');
            return;
        }

        $this->solicitud     = $evento->solicitud_id;
        $this->facilitador   = $evento->facilitador_id;
        $this->horaInicio = Carbon::createFromFormat('H:i:s', $evento->hora_inicio)->format('H:i');
        $this->horaFin    = Carbon::createFromFormat('H:i:s', $evento->hora_fin)->format('H:i');

        $this->actividad     = $evento->descripcion;
        $this->fechaSeleccionada = $evento->fecha;
        $this->colorEvento = $evento->color;

        $this->show = true;
    }

    protected $listeners = 
    [
        'abrirModalEvento' => 'cargarEvento',
        'abrirModalDia' => 'recibirFecha',
    ];

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

    public function mount() 
    {
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

    public function abrirModalDia()
    {
        $this->showModalDia = true;
    }

    public function cerrar()
    {
        $this->show = false;
        $this->showModalDia = false;

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

    public function guardarEvento()
    {
        // dd($this->colorEvento)
        $this->validate([
            'solicitud'   => 'required|exists:solicitudes,id',
            'facilitador' => 'required|exists:facilitadores,id',
            'horaInicio'  => 'required|date_format:H:i',
            'horaFin'     => 'required|date_format:H:i|after:horaInicio',
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

                ]);

                if ($cambioFacilitador) {
                    Solicitud::where('id', $this->solicitud)->update([
                        'facilitador' => $this->facilitador,
                    ]);
                }
            }
        } else {
            $materia = Solicitud::where('id', $this->solicitud)->value('materia');

            Agenda::create([
                'solicitud_id'   => $this->solicitud,
                'facilitador_id' => $this->facilitador,
                'fecha'          => $this->fechaSeleccionada,
                'hora_inicio'    => $this->horaInicio,
                'hora_fin'       => $this->horaFin,
                'descripcion'    => $this->actividad,
                'materia'        => $materia,
                'color'          => $this->colorEvento, 
            ]);

            Solicitud::where('id', $this->solicitud)->update([
                'facilitador' => $this->facilitador,
                'estatus_id'  => 2
            ]);
        }

        $this->cerrar();

        return redirect()->route('calendario.index')
            ->with('success', '¡Evento guardado correctamente!');
    }

    public function render()
    {
        return view('livewire.evento-modal');
    }
}
