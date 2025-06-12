<?php

namespace App\Livewire\Solicitud;

use Livewire\Component;
use App\Models\Solicitante;
use App\Models\SepomexColonia;
use Masmerise\Toaster\Toaster;
use App\Traits\ConvenioTraits\HandleDocumentos;

class DatosPersonales extends Component
{
    use HandleDocumentos;

    public $personaSeleccionada;
    public $escolaridades = [];
    public $ocupaciones = [];
    public $colonias;

    public function mount($personaSeleccionada)
    {
        $this->personaSeleccionada = $personaSeleccionada;
        $this->escolaridades = $this->escolaridades();
        $this->ocupaciones = $this->ocupaciones();
        $this->cargarColonias($this->personaSeleccionada['cp'] ?? '');
    }

    public function updatedPersonaSeleccionadaCp()
    {
        $cp = $this->personaSeleccionada['cp'] ?? '';
        if (strlen($cp) !== 5) return;
        $this->cargarColonias($cp);

        if ($this->colonias->isNotEmpty()) {
            $col = $this->colonias->first();
            $this->personaSeleccionada['municipio'] = $col->municipio;
            $this->personaSeleccionada['entidad_federativa'] = $col->estado;
        } else {
            $this->personaSeleccionada['municipio'] = '';
            $this->personaSeleccionada['entidad_federativa'] = '';
        }
    }

    private function cargarColonias($cp)
    {
        $this->colonias = $cp
            ? SepomexColonia::where('codigo_postal', $cp)->get()
            : collect();
    }

    public function actualizarDatos()
    {
        $id = $this->personaSeleccionada['id'] ?? null;
        if (!$id) return;

        // Actualiza campos básicos
        $data = collect($this->personaSeleccionada)
            ->only([
                'nombre', 
                'apellido_p', 
                'apellido_m',
                'sexo', 
                'edad', 
                'fecha_nacimiento',
                'escolaridad', 
                'ocupacion', 
                'nacionalidad',
                'razon_social', 
                'rfc', 
                'instrumento', 
                'fecha_instrumento',
                'tipo_domicilio', 
                'calle', 
                'cp', 
                'colonia',
                'municipio', 
                'entidad_federativa'
            ])
            ->toArray();

        Solicitante::where('id', $id)->update($data);

        Toaster::success('Información actualizada correctamente !');
    }

    public function render()
    {
        return view('livewire.solicitud.datos-personales');
    }
}
