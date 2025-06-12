<?php

namespace App\Livewire\Solicitud;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Documento;

class DocumentosPersona extends Component
{
    use WithFileUploads;

    public $personaId;
    public $nuevos = [];

    public function actualizar($tipo)
    {
        if (!isset($this->nuevos[$tipo])) {
            return;
        }

        $archivo = $this->nuevos[$tipo];
        $nombreArchivo = uniqid() . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('documentos', $nombreArchivo, 'public');

        Documento::updateOrCreate(
            ['solicitante_id' => $this->personaId, 'tipo' => $tipo],
            ['ruta' => $ruta]
        );

        unset($this->nuevos[$tipo]);
    }

    public function render()
    {
        $documentos = Documento::where('solicitante_id', $this->personaId)->get();

        return view('livewire.solicitud.documentos-persona', compact('documentos'));
    }
}
