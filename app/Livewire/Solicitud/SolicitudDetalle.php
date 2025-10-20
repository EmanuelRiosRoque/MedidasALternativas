<?php

namespace App\Livewire\Solicitud;

use Flux\Flux;
use App\Models\Correo;
use App\Models\Facilitador;
use App\Models\Solicitud;
use App\Models\Solicitante;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Traits\ConvenioTraits\HandleDocumentos;

class SolicitudDetalle extends Component
{
    use HandleDocumentos;

    /** ==============================
     *  PROPIEDADES PRINCIPALES
     * ============================== */
    public $solicitud;
    public $solicitudId;
    public $materia;

    public $solicitantes = [];
    public $invitados = [];
    public $facilitadores = [];

    // Co-mediador
    public $coMediadorId = '';

    // Correos asociados
    public $correosSolicitantes;
    public $correosInvitados;

    /** ==============================
     *  CICLO DE VIDA
     * ============================== */
    protected $listeners = ['proceso-actualizado' => 'refrescarSolicitud'];

    public function mount($solicitudId)
    {
        // Cargar solicitud principal
        $this->solicitud = Solicitud::with('facilitador')->findOrFail($solicitudId);
        $this->solicitudId = (int) $solicitudId;

        // Cargar materia actual
        $this->materia = $this->solicitud->materia;

        // Cargar facilitadores disponibles
        $this->facilitadores = Facilitador::orderBy('nombre')->get();

        // Cargar solicitantes e invitados
        $this->solicitantes = Solicitante::where('solicitud_id', $this->solicitudId)
            ->where('tipo_solicitante', 'solicitante')
            ->get();

        $this->invitados = Solicitante::where('solicitud_id', $this->solicitudId)
            ->where('tipo_solicitante', 'invitado')
            ->get();

        // Cargar correos asociados
        $solicitanteIds = $this->getPersonaIds($this->solicitudId, 'solicitante');
        $invitadoIds    = $this->getPersonaIds($this->solicitudId, 'invitado');

        $this->correosSolicitantes = $this->getCorreosBySolicitantes($solicitanteIds);
        $this->correosInvitados    = $this->getCorreosBySolicitantes($invitadoIds);
    }

    /** ==============================
     *  FUNCIONES AUXILIARES
     * ============================== */
    private function getPersonaIds(int $solicitudId, string $tipo = 'solicitante')
    {
        return Solicitante::where('solicitud_id', $solicitudId)
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

    public function refrescarSolicitud(int $id): void
    {
        $this->solicitud = Solicitud::find($id);
    }

    public function updatedMateria($value)
    {
        $this->solicitud->update(['materia' => $value]);
        Toaster::success('Materia actualizada correctamente.');
    }

    /** ==============================
     *  CO-MEDIADOR
     * ============================== */
    public function guardarCoMediador(): void
    {
        $this->validate([
            'coMediadorId' => ['required'],
        ], [
            'coMediadorId.required' => 'Seleccione un co-mediador.',
        ]);

        $this->solicitud->forceFill([
            'co_mediador_id' => (int) $this->coMediadorId,
        ])->save();

        $this->solicitud->refresh()->loadMissing('coMediador');

        $this->reset('coMediadorId');
        Flux::modal('co-mediador')->close();

        Toaster::success('Co-mediador asignado correctamente.');
    }

    /** ==============================
     *  RENDER
     * ============================== */
    public function render()
    {
        return view('livewire.solicitud.solicitud-detalle', [
            'solicitantes' => $this->solicitantes,
            'invitados' => $this->invitados,
        ]);
    }
}
