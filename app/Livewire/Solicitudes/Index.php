<?php

namespace App\Livewire\Solicitudes;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
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

        // Paginación (ejemplo: 10 por página)
        $solicitudes = $solicitudesQuery->paginate(5);

        $this->numSolicitudes = $solicitudes->total();

        return view('livewire.solicitudes.index', [
            'solicitudes' => $solicitudes
        ]);
    }
}
