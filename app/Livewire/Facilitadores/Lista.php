<?php

namespace App\Livewire\Facilitadores;

use App\Models\Facilitador;
use Livewire\Component;
use Livewire\WithPagination;

class Lista extends Component
{
    use WithPagination;

    public $search = '';
    public $numFacilitadores = 0;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $facilitadoresQuery = Facilitador::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('clave', 'like', '%' . $this->search . '%');
                });
            });


        // contar antes de la paginación
        $this->numFacilitadores = $facilitadoresQuery->count();

        $facilitadores = $facilitadoresQuery
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.facilitadores.lista', [
            'facilitadores' => $facilitadores
        ]);
    }
}
