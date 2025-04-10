<?php

namespace App\Livewire;

use Livewire\Component;

class Facilitadores extends Component
{
	public int $tab = 1;
    public string $tipo_facilitador = '';
    public string $autoridad_certificacion_facilitador = '';
    public string $autorizacion_facilitador = '';
    public string $especializacion_facilitador = '';
    public string $especializacion_arbitra_facilitador ='';
    public string $quejas_recibidas_facilitador = '';
    public string $tiene_resolucion = '';
    public string $visustas_supervision = '';
    public string $dictamen_cj = '';
    public function render()
    {
        return view('livewire.facilitadores');
    }
}
