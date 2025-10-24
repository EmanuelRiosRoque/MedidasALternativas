<?php

namespace App\Livewire\Solicitud;

use Livewire\Component;
use App\Models\Observacion;
use Masmerise\Toaster\Toaster;

class Observaciones extends Component
{
    public $observaciones = [];
    public $solicitud; // 👈 se recibe desde fuera (modelo completo)
    public $obs = [
        'fecha_observacion' => null,
        'observacion'       => null,
    ];

    public function mount($solicitud)
    {
        $this->solicitud = $solicitud;

        $this->observaciones = Observacion::where('solicitud_id', $solicitud->id)
            ->orderByDesc('fecha_observacion')
            ->get();
    }

    /** ==================================================
     *  OBSERVACIONES
     * ================================================== */
    public function agregarObservacion()
    {
        $this->validate([
            'obs.fecha_observacion' => 'required|date',
            'obs.observacion'       => 'required|string|max:500',
        ], [
            'obs.fecha_observacion.required' => 'La fecha es obligatoria.',
            'obs.observacion.required'       => 'La observación no puede estar vacía.',
        ]);

        Observacion::create([
            'solicitud_id'      => $this->solicitud->id,
            'fecha_observacion' => $this->obs['fecha_observacion'],
            'observacion'       => $this->obs['observacion'],
        ]);

        // Limpiar el formulario
        $this->obs = ['fecha_observacion' => null, 'observacion' => null];

        // Refrescar la lista
        $this->observaciones = Observacion::where('solicitud_id', $this->solicitud->id)
            ->orderByDesc('fecha_observacion')
            ->get();

        Toaster::success('¡Observación agregada!');
    }

    public function borrarObservacion(int $id): void
    {
        Observacion::where('solicitud_id', $this->solicitud->id)
            ->where('id', $id)
            ->delete();

        // Refrescar la lista
        $this->observaciones = Observacion::where('solicitud_id', $this->solicitud->id)
            ->orderByDesc('fecha_observacion')
            ->get();

        Toaster::success('Observación eliminada.');
    }

    public function render()
    {
        return view('livewire.solicitud.observaciones', [
            'observaciones' => $this->observaciones,
        ]);
    }
}
