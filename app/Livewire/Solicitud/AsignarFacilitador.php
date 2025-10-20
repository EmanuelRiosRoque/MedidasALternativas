<?php

namespace App\Livewire\Solicitud;

use App\Models\Facilitador;
use App\Models\Solicitud;
use App\Models\Solicitante;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Flux\Flux;

class AsignarFacilitador extends Component
{
    public $facilitadores = [];
    public $solicitud;
    public $solicitudId;

    public $solicitantes = [];
    public $invitados = [];

    // Campos de asignación
    public $facilitadorSolicitanteId = '';
    public $facilitadorInvitadoId = '';
    public $coMediadorId = '';

    // Nuevos campos de fecha y hora (solicitante)
    public $fechaAsignacionSolicitante;
    public $horaInicioSolicitante;
    public $horaFinSolicitante;

    // Nuevos campos de fecha y hora (invitado)
    public $fechaAsignacionInvitado;
    public $horaInicioInvitado;
    public $horaFinInvitado;

    protected $listeners = ['refreshFacilitadores' => '$refresh'];

    public function mount(Solicitud $solicitud)
    {
        // Cargar solicitud con relaciones
        $this->solicitud = Solicitud::with([
            'facilitadorSolicitante',
            'facilitadorInvitado',
            'coMediador'
        ])->findOrFail($solicitud->id);

        $this->solicitudId = $solicitud->id;
        $this->facilitadores = Facilitador::orderBy('nombre')->get();

        // Cargar solicitantes e invitados
        $this->solicitantes = Solicitante::where('solicitud_id', $solicitud->id)
            ->where('tipo_solicitante', 'solicitante')
            ->get();

        $this->invitados = Solicitante::where('solicitud_id', $solicitud->id)
            ->where('tipo_solicitante', 'invitado')
            ->get();

        // ========================
        // Prellenar datos existentes
        // ========================
        $this->facilitadorSolicitanteId = $this->solicitud->facilitador_solicitante_id ?? '';
        $this->fechaAsignacionSolicitante = $this->solicitud->fecha_asignacion_solicitante ?? '';
        $this->horaInicioSolicitante = $this->solicitud->hora_inicio_solicitante ?? '';
        $this->horaFinSolicitante = $this->solicitud->hora_fin_solicitante ?? '';

        $this->facilitadorInvitadoId = $this->solicitud->facilitador_invitado_id ?? '';
        $this->fechaAsignacionInvitado = $this->solicitud->fecha_asignacion_invitado ?? '';
        $this->horaInicioInvitado = $this->solicitud->hora_inicio_invitado ?? '';
        $this->horaFinInvitado = $this->solicitud->hora_fin_invitado ?? '';

        $this->coMediadorId = $this->solicitud->co_mediador_id ?? '';
    }

    /** ==============================
     *  MÉTODOS DE ASIGNACIÓN Y UPDATE
     * ============================== */

    public function guardarFacilitadorSolicitante()
    {
        $this->validate([
            'fechaAsignacionSolicitante' => 'nullable|date',
            'horaInicioSolicitante' => 'nullable|date_format:H:i',
            'horaFinSolicitante' => 'nullable|date_format:H:i|after:horaInicioSolicitante',
            'facilitadorSolicitanteId' => 'nullable|exists:facilitadores,id',
        ]);

        $datos = array_filter([
            'facilitador_solicitante_id' => $this->facilitadorSolicitanteId,
            'fecha_asignacion_solicitante' => $this->fechaAsignacionSolicitante,
            'hora_inicio_solicitante' => $this->horaInicioSolicitante,
            'hora_fin_solicitante' => $this->horaFinSolicitante,
        ], fn($value) => !is_null($value) && $value !== '');

        if (empty($datos)) {
            Toaster::info('No se detectaron cambios en la asignación del solicitante.');
            return;
        }

        $this->solicitud->update($datos);

        $this->refrescarRelaciones();
        Flux::modal('asignar-facilitador-solicitante')->close();

        Toaster::success('Datos de solicitante actualizados correctamente.');
        $this->dispatch('refreshFacilitadores');
    }

    public function guardarFacilitadorInvitado()
    {
        $this->validate([
            'fechaAsignacionInvitado' => 'nullable|date',
            'horaInicioInvitado' => 'nullable|date_format:H:i',
            'horaFinInvitado' => 'nullable|date_format:H:i|after:horaInicioInvitado',
            'facilitadorInvitadoId' => 'nullable|exists:facilitadores,id',
        ]);

        $datos = array_filter([
            'facilitador_invitado_id' => $this->facilitadorInvitadoId,
            'fecha_asignacion_invitado' => $this->fechaAsignacionInvitado,
            'hora_inicio_invitado' => $this->horaInicioInvitado,
            'hora_fin_invitado' => $this->horaFinInvitado,
        ], fn($value) => !is_null($value) && $value !== '');

        if (empty($datos)) {
            Toaster::info('No se detectaron cambios en la asignación del invitado.');
            return;
        }

        $this->solicitud->update($datos);

        $this->refrescarRelaciones();
        Flux::modal('asignar-facilitador-invitado')->close();

        Toaster::success('Datos de invitado actualizados correctamente.');
        $this->dispatch('refreshFacilitadores');
    }

    public function guardarCoMediador()
    {
        $this->validate([
            'coMediadorId' => 'required|exists:facilitadores,id',
        ]);

        $this->solicitud->update([
            'co_mediador_id' => $this->coMediadorId,
        ]);

        $this->refrescarRelaciones();
        Flux::modal('co-mediador')->close();

        Toaster::success('Co-mediador actualizado correctamente.');
        $this->dispatch('refreshFacilitadores');
    }

    /** ==============================
     *  AUXILIARES
     * ============================== */
    private function refrescarRelaciones()
    {
        $this->solicitud->refresh()->load([
            'facilitadorSolicitante',
            'facilitadorInvitado',
            'coMediador'
        ]);
    }

    public function render()
    {
        return view('livewire.solicitud.asignar-facilitador', [
            'solicitantes' => $this->solicitantes,
            'invitados' => $this->invitados,
        ]);
    }
}
