<?php

namespace App\Livewire\Solicitud;

use App\Models\Correo;
use Livewire\Component;
use App\Models\Telefono;
use App\Models\Documento;
use App\Models\Solicitante;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\SepomexColonia;
use App\Traits\ConvenioTraits\HandleDocumentos;

class VerPersonas extends Component
{
    use HandleDocumentos, WithFileUploads;

    public $personaSeleccionada = [];
    public $documentosPersona = [];
    public $correos = [];
    public $telefonos = [];
    public $solicitudId;

    public function mount($solicitudId)
    {
        $this->solicitudId = $solicitudId;
        $this->colonias = [];
        $this->personaSeleccionada = [];
    }

    public function seleccionarPersona($id)
    {
        if ($this->personaSeleccionada && $this->personaSeleccionada['id'] === $id) {
            return;
        }

        $persona = Solicitante::with(['correos', 'telefonos', 'estatus', 'facilitador'])->find($id);

        if (!$persona) return;

        $data = $persona->toArray();

        $this->personaSeleccionada = null; // Limpiamos
        $this->personaSeleccionada = $data;// Cargamos nuevo datos

        $this->correos = $data['correos'] ?? [];
        $this->telefonos = $data['telefonos'] ?? [];
        $this->cargarDocumentos();
    }
 

    private function cargarDocumentos()
    {
        $id = $this->personaSeleccionada['id'] ?? null;
        if ($id) {
            $this->documentosPersona = Documento::where('solicitante_id', $id)->get(['tipo', 'ruta']);
        }
    }


    public function render()
    {
        $escolaridades = $this->escolaridades();
        $ocupaciones = $this->ocupaciones();
        $personas = Solicitante::where('solicitud_id', $this->solicitudId)->get();
    
        return view('livewire.solicitud.ver-personas', compact('personas', 'escolaridades', 'ocupaciones'));
    }
}
