<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SepomexColonia;

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
    public string $visitas_supervision = '';
    public string $dictamen_cj = '';


    public $cp_solicitante = '';
	/** @var \Illuminate\Support\Collection|\App\Models\SepomexColonia[] */
    public $colonias = [];
    public $colonia = '';
    public $entidad_federativa_solicitante = '';
    public $municipio_solicitante = '';


    
	public string $correo_temp = '';
    public string $telefono_temp = '';
    public array $correos = [];
    public array $telefonos = [];

    public function updatedCpSolicitante()
    {
        $this->colonias = SepomexColonia::where('codigo_postal', $this->cp_solicitante)
            ->get();

        if ($this->colonias->isNotEmpty()) {
            $this->entidad_federativa_solicitante = $this->colonias->first()->estado;
            $this->municipio_solicitante = $this->colonias->first()->municipio;
        } else {
            $this->entidad_federativa_solicitante = '';
            $this->municipio_solicitante = '';
        }

        $this->colonia = '';
    }



    
	public function agregarCorreo()
    {
        $correo = trim($this->correo_temp);
    
        if ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->correos[] = strtoupper($correo);
            $this->correo_temp = '';
        }
    }
    
    public function eliminarCorreo($index)
    {
        unset($this->correos[$index]);
        $this->correos = array_values($this->correos);
    }
    
    public function agregarTelefono()
    {
        $telefono = trim($this->telefono_temp);
    
        if ($telefono !== '') {
            $this->telefonos[] = $telefono;
            $this->telefono_temp = '';
        }
    }
    
    public function eliminarTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos);
    }


    public function render()
    {
        return view('livewire.facilitadores');
    }
}
