<?php

namespace App\Livewire\Solicitud;

use App\Models\Agenda;
use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Solicitante;

use Masmerise\Toaster\Toaster;
use App\Traits\ConvenioTraits\HandleDocumentos;


class Index extends Component
{
	use HandleDocumentos;


	public $mostrarModal = false;
    public $medio_envio = 'correo';

    public $solicitudId;
    public $solicitantes = [];
    public $invitados = [];
    public $solicitud;
    public $evento;
    public $escolaridades;
    public $ocupaciones;

    public $persona;

    //Materia
    public $materia;

    // Persona fisica
    public $nombre;
    public $apellido_p;
    public $apellido_m;
    public $rfc;
    public $sexo;
    public $edad;
    public $fecha_nacimiento;
    public $ocupacion;
    public $escolaridad;
    public $correos = [];
    public $telefonos = [];



    // Montar con ID
    public function mount($solicitudId)
    {
        $this->escolaridades = $this->escolaridades();
        $this->ocupaciones = $this->ocupaciones();

        $this->solicitud = Solicitud::with('facilitador')->findOrFail($solicitudId);
        $this->evento = Agenda::where('solicitud_id', $solicitudId)
            ->where('activo', 1)
            ->first();
        

        $this->solicitudId = $solicitudId;

        $this->solicitantes = Solicitante::where('tipo_solicitante', 'solicitante')
            ->where('solicitud_id', $this->solicitudId)
            ->get();

        $this->invitados = Solicitante::where('tipo_solicitante', 'invitado')
            ->where('solicitud_id', $this->solicitudId)
            ->get();

        $this->materia = $this->solicitud->materia;
    }

    public function updatedMateria($value)
    {
        $this->solicitud->update(['materia' => $value]);
        Toaster::success('Materia actualizada correctamente !');
    }


    public function render()
    {
        return view('livewire.solicitud.index', [
            'solicitantes' => $this->solicitantes,
            'invitados' => $this->invitados,
        ]);
    }
}
