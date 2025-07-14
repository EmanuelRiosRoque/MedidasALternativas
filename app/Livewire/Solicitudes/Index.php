<?php

namespace App\Livewire\Solicitudes;

use App\Models\Solicitud;
use Livewire\Component;

class Index extends Component
{
    public $numSolicitudes = 0;
    public $search = '';

    public function render()
    {
        $usuario = auth()->user();

        $solicitudesQuery = Solicitud::query()
            ->when(!$usuario->hasRole('admin'), function ($query) use ($usuario) {
                $query->when($usuario->hasRole('familiar'), function ($query) {
                    $query->where('materia', 'familiar');
                })
                ->when($usuario->hasRole('civil'), function ($query) {
                    $query->whereIn('materia', ['civil', 'mercantil']);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('folio_materia', 'like', "%{$this->search}%")
                    ->orWhere('numero_ticket', 'like', "%{$this->search}%");
                });
            });

        $solicitudes = $solicitudesQuery->get();
        $this->numSolicitudes = $solicitudes->count();

        return view('livewire.solicitudes.index', [
            'solicitudes' => $solicitudes
        ]);
    }

}

