<?php

namespace App\Livewire\Solicitudes;

use App\Models\Solicitud;
use Livewire\Component;

class Index extends Component
{
    public $numSolicitudes = 0;
    
    public function render()
    {
        $usuario = auth()->user();

        $solicitudes = Solicitud::query()
            ->when($usuario->hasRole('familiar'), function ($query) {
                $query->where('materia', 'familiar');
            })
            ->when($usuario->hasRole('civil'), function ($query) {
                $query->whereIn('materia', ['civil', 'mercantil']);
            });

        // Obtener la cantidad directamente
        $this->numSolicitudes = $solicitudes->count();

        return view('livewire.solicitudes.index', [
            'solicitudes' => $solicitudes->get()
        ]);
    }
}

