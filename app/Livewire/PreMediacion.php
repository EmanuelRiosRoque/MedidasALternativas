<?php

namespace App\Livewire;

use App\Models\Solicitud;
use Livewire\Component;

class PreMediacion extends Component
{
    public function render()
    {
        $solicitudes = Solicitud::all();

        return view('livewire.pre-mediacion',[
            'solicitudes' => $solicitudes
        ]);
    }
}
