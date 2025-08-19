<?php

namespace App\Livewire\Solicitud;

use Flux\Flux;
use Carbon\Carbon;
use App\Models\Agenda;
use App\Models\Correo;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Solicitante;
use Masmerise\Toaster\Toaster;
use App\Mail\InvitacionMediacion;
use Illuminate\Support\Facades\Mail;
use App\Traits\ConvenioTraits\HandleDocumentos;


class Index extends \Livewire\Component  
{
	use HandleDocumentos;


	public $mostrarModal = false;
    public $medio_envio = 'correo';

    public $solicitudId;
    public $solicitantes = [];
    public $invitados = [];
    public $solicitud;
    public $evento;
    public $segSesion;
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
    public $horarios = [];

    public $horaInicio = '';
    public $horaFin = '';
    public $horaInicioInvitado = '';
    public $horaFinInvitado = '';
    public $fechaSegundaInv;
    public $urlSegundaInv;

    public $correosSolicitantes;
    public $correosInvitados;

    // Montar con ID
    public function mount($solicitudId)
    {
        $this->escolaridades = $this->escolaridades();
        $this->ocupaciones = $this->ocupaciones();

        $this->solicitud = Solicitud::with('facilitador')->findOrFail($solicitudId);

        $evento = $this->evento = Agenda::where('solicitud_id', $solicitudId)
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
        $this->horarios = $this->generarHorarios('09:00', '19:00');

        $solicitanteIds = $this->getPersonaIds($evento, 'solicitante');
        $invitadoIds = $this->getPersonaIds($evento, 'invitado');

        $this->correosSolicitantes = $this->getCorreosBySolicitantes($solicitanteIds);
        $this->correosInvitados = $this->getCorreosBySolicitantes($invitadoIds);

        $this->segSesion = Agenda::where('solicitud_id', $this->solicitudId)
        ->where('activo', 1)
        ->where('estatus_id', 7) // Segunda sesion
        ->first();
    }

    public function updatedMateria($value)
    {
        $this->solicitud->update(['materia' => $value]);
        Toaster::success('Materia actualizada correctamente !');
    }


    private function getPersonaIds($evento, $tipo = 'solicitante')
    {
        return Solicitante::where('solicitud_id', $evento->solicitud_id)
            ->where('tipo_solicitante', $tipo) 
            ->whereNotNull('facilitador_id')
            ->whereNotNull('estatus_id')
            ->pluck('id');
    }

      private function getCorreosBySolicitantes($solicitanteIds)
    {
        return Correo::whereIn('solicitante_id', $solicitanteIds)
            ->pluck('email');
    }

    public function segundaInvitacion() {
        $evento = $this->evento;

        Agenda::create([
            'solicitud_id'      => $this->solicitudId,
            'facilitador_id'    => $evento->facilitador_id,
            'opcion_invitacion' => $evento->opcion_invitacion,
            'fecha'             => $this->fechaSegundaInv,
            'hora_inicio'       => $this->horaInicio,
            'hora_fin'          => $this->horaFin,
            'hora_inicio_invitado' => $this->horaInicioInvitado ?: null,
            'hora_fin_invitado'    => $this->horaFinInvitado ?: null,
            'descripcion'       => $evento->descripcion,
            'materia'           => $evento->materia,
            'color'             => $evento->color,
            'observacion'       => $evento->observacion,
            'url'               => $this->urlSegundaInv,
            'estatus_id'        => 7, // Segunda Invitación
            'activo'            => 1,
        ]);

        $horario = 
        Carbon::parse($this->evento->hora_inicio)->format('H:i') . ' - ' .
        Carbon::parse($this->evento->hora_fin)->format('H:i');
        
        $horarioInvitados = 
        Carbon::parse($this->evento->hora_inicio_invitado)->format('H:i') . ' - ' .
        Carbon::parse($this->evento->hora_fin_invitado)->format('H:i');

        $fecha = Carbon::parse($this->fechaSegundaInv)->translatedFormat('l d \d\e F Y');
        
        foreach ($this->correosSolicitantes as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Solicitante', $this->urlSegundaInv, $horario, $fecha, 2));
        }

        foreach ($this->correosInvitados as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Invitado', $this->urlSegundaInv, $horarioInvitados, $fecha, 2));
        }

        $this->segSesion = Agenda::where('solicitud_id', $this->solicitudId)
        ->where('activo', 1)
        ->where('estatus_id', 7) // Segunda sesion
        ->first();

        Flux::modal('edit-profile')->close();

        Toaster::success('Invitación enviada correctamente!');

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
        return view('livewire.solicitud.index', [
            'solicitantes' => $this->solicitantes,
            'invitados' => $this->invitados,
        ]);
    }
}
