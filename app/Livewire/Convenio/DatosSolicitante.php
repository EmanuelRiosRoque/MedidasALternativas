<?php

namespace App\Livewire\Convenio;

use Livewire\Component;

class DatosSolicitante extends Component
{
    public $materia;
    public $mediosInvitado = [];
    public $persona;
    public $doc_representante;
    public $escolaridades = [];
    public $ocupaciones = [];
    public $modalidad;
    public $colonias = [];  
    public $representante;
    public $nombre;
    public $apellido_p;
    public $apellido_m;
    public $sexo;
    public $edad;
    public $fecha_nacimiento;
    public $escolaridad;
    public $ocupacion;
    public $nacionalidad;
    public $tipo_domicilio;
    public $calle;
    public $colonia;
    public $municipio;
    public $entidad_federativa;
    public $correo;
    public $cp;

    public function render()
    {
        return view('livewire.convenio.datos-solicitante');
    }
}
