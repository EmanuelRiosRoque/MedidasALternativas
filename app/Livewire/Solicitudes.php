<?php

namespace App\Livewire;

use App\Models\Solicitud;
use Livewire\Component;

class Solicitudes extends Component
{
    public function render()
    {
        $solicitudes = Solicitud::all();

        return view('livewire.solicitudes',[
            'solicitudes' => $solicitudes
        ]);
    }
}
