<?php

namespace App\Livewire\Convenio;

use Livewire\Component;

class Solicitante extends Component
{
    public string $tipo = 'fisica'; // puede ser 'fisica' o 'moral'

    // Persona física
    public string $nombre_solicitante = '';
    public string $sexo_solicitante = '';
    public string $edad_solicitante = '';
    public string $fecha_nacimiento_solicitante = '';
    public string $escolaridad_solicitante = '';
    public string $ocupacion_solicitante = '';
    public string $nacionalidad_solicitante = '';
    public string $tipo_domicilio_solicitante = '';
    public string $calle_solicitante = '';
    public string $colonia_solicitante = '';
    public string $municipio_solicitante = '';
    public string $entidad_federativa_solicitante = '';
    public string $correo_solicitante = '';
    public string $cp_solicitante = '';

    // Persona moral
    public string $razon_social_solicitante = '';
    public string $rfc_solicitante = '';
    public string $instrumento_solicitante = '';
    public string $fecha_instrumento_solicitante = '';
    public string $telefono_solicitante = '';

    public function render()
    {
        return view('livewire.convenio.solicitante');
    }
}
