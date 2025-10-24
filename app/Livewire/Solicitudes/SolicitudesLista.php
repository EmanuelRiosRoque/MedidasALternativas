<?php

namespace App\Livewire\Solicitudes;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudesLista extends Component
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
        ->whereIn('estatus_id', [1, 2])
        ->where(function ($q) {
            $q->whereNull('tipo_proceso_id')
              ->orWhere('tipo_proceso_id', 1);
        })
        ->when(!$usuario->hasRole('admin'), function ($query) use ($usuario) {
            $query->when($usuario->hasRole('familiar'), function ($q) {
                    $q->where('materia', 'familiar');
                })
                ->when($usuario->hasRole('civil'), function ($q) {
                    $q->whereIn('materia', ['civil', 'mercantil']);
                });
        })
        ->when($this->search, function ($query) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('folio_materia', 'like', "%{$search}%")
                  ->orWhere('numero_ticket', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc'); // 👈 aquí agregamos el orden descendente

    $solicitudes = $solicitudesQuery->paginate(5);

    $this->numSolicitudes = $solicitudes->total();

    return view('livewire.solicitudes.solicitudes-lista', [
        'solicitudes' => $solicitudes
    ]);
}

}
