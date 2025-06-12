<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Correo;
use Livewire\Component;
use App\Models\Solicitante;
use Masmerise\Toaster\Toaster;
use App\Mail\InvitacionMediacion;
use App\Models\Agenda;
use Illuminate\Support\Facades\Mail;

class InvitacionEvento extends Component
{
    public ?string $enlaceReunion = null;
    public $evento;
    public $eventoId;
    public $correosSolicitantes;
    public $correosInvitados;

    public function mount($evento)
    {
        $this->evento = $evento;
        $this->eventoId = $evento->id;

        if (!$evento || !isset($evento->solicitud_id)) {
            $this->correosSolicitantes = collect();
            $this->correosInvitados = collect();
            return;
        }

        $solicitanteIds = $this->getPersonaIds($evento, 'solicitante');
        $invitadoIds = $this->getPersonaIds($evento, 'invitado');

        $this->correosSolicitantes = $this->getCorreosBySolicitantes($solicitanteIds);
        $this->correosInvitados = $this->getCorreosBySolicitantes($invitadoIds);
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

    public function store()
    {
        // dd($this->correos);
        $this->validate([
            'enlaceReunion' => ['required'],
        ]);

        
        $eventoAddUrl = Agenda::find($this->eventoId);

        if ($eventoAddUrl) {
            $eventoAddUrl->url = $this->enlaceReunion;
            $eventoAddUrl->save();
            $this->evento = $eventoAddUrl->refresh(); // Recargar datos actualizados
        }

        $horario = 
        Carbon::parse($this->evento->hora_inicio)->format('H:i') . ' - ' .
        Carbon::parse($this->evento->hora_fin)->format('H:i');

        
        $horarioInvitados = 
        Carbon::parse($this->evento->hora_inicio_invitado)->format('H:i') . ' - ' .
        Carbon::parse($this->evento->hora_fin_invitado)->format('H:i');
        
        $fecha = Carbon::parse($this->evento->fecha)->translatedFormat('l d \d\e F Y');

        foreach ($this->correosSolicitantes as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Solicitante', $this->enlaceReunion, $horario, $fecha));
        }

        foreach ($this->correosInvitados as $correo) {
            Mail::to($correo)->send(new InvitacionMediacion('Invitado', $this->enlaceReunion, $horarioInvitados, $fecha));
        }

        Toaster::success('Invitación enviada correctamente!');
    }

    public function render()
    {
        return view('livewire.invitacion-evento');
    }
}
