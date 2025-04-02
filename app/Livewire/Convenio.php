<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class Convenio extends Component
{
    use WithFilePond;

    public int $tab = 1;
    // Input Radios
    public  $modalidad;
    public  $materia;
    public  $tipo_convenio;

    //Datos solicitante
    public  $persona;
    public  $representante;

    //Datos invitado
    public  $persona_invitado;

    // Documentos
    public  $identificacion;
    public  $acta_notarial;




    public function render()
    {
        return view('livewire.convenio');
    }
}
