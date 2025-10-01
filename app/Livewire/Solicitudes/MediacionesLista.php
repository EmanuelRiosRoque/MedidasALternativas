<?php

namespace App\Livewire\Solicitudes;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class MediacionesLista extends Component
{
    use WithPagination;

    public $numSolicitudes = 0;
    public $search = '';

    // Para que al buscar vuelva a la página 1
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuario = auth()->user();

     $solicitudesQuery = Solicitud::query()
            ->where('tipo_proceso_id', 2) // ⬅️ delimita a tipo_proceso_id = 2
            ->when(!$usuario->hasRole('admin'), function ($query) use ($usuario) {
                $query->when($usuario->hasRole('familiar'), function ($q) {
                        $q->where('materia', 'familiar');
                    })
                    ->when($usuario->hasRole('civil'), function ($q) {
                        $q->whereIn('materia', ['civil', 'mercantil']);
                    });
            })
            ->when($this->search, function ($query) {
                $search = $this->search;
                $query->where(function ($q) use ($search) {
                    $q->where('folio_materia', 'like', "%{$search}%")
                    ->orWhere('numero_ticket', 'like', "%{$search}%");
                });
            });

        // Paginación (ejemplo: 10 por página)
        $solicitudes = $solicitudesQuery->paginate(5);

        $this->numSolicitudes = $solicitudes->total();

        return view('livewire.solicitudes.mediaciones-lista', [
            'solicitudes' => $solicitudes
        ]);
    }
}
