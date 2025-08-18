<?php

namespace App\Livewire\Solicitud;

use Livewire\Component;
use App\Models\Correo;
use App\Models\Telefono;

class ContactoPersona extends Component
{   
    public $correos = [];
    public $telefonos = [];
    public $nuevoCorreo = '';
    public $nuevoTelefono = '';
    public $personaId;

    public function mount($correos, $telefonos, $personaId)
    {
        $this->correos = $correos;
        $this->telefonos = $telefonos;
        $this->personaId = $personaId;
    }

    public function actualizarCorreo($index)
    {
        $correo = $this->correos[$index] ?? null;

        if ($correo && isset($correo['id'])) {
            Correo::where('id', $correo['id'])->update([
                'email' => $correo['email']
            ]);
        }
    }

    public function actualizarTelefono($index)
    {
        $telefono = $this->telefonos[$index] ?? null;

        if ($telefono && isset($telefono['id'])) {
            Telefono::where('id', $telefono['id'])->update([
                'numero' => $telefono['numero']
            ]);
        }
    }

    public function agregarCorreo()
    {
        if ($this->nuevoCorreo && $this->personaId) {
            Correo::create([
                'solicitante_id' => $this->personaId,
                'email' => $this->nuevoCorreo,
            ]);

            $this->nuevoCorreo = '';
            $this->recargarCorreos();
        }
    }

    public function agregarTelefono()
    {
        if ($this->nuevoTelefono && $this->personaId) {
            Telefono::create([
                'solicitante_id' => $this->personaId,
                'numero' => $this->nuevoTelefono,
            ]);

            $this->nuevoTelefono = '';
            $this->recargarTelefonos();
        }
    }

    public function recargarCorreos()
    {
        $this->correos = Correo::where('solicitante_id', $this->personaId)->get()->toArray();
    }

    public function recargarTelefonos()
    {
        $this->telefonos = Telefono::where('solicitante_id', $this->personaId)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.solicitud.contacto-persona');
    }
}
