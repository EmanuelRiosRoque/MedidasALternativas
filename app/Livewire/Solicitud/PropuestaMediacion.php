<?php

namespace App\Livewire\Solicitud;

use App\Models\Cja;
use Livewire\Component;
use App\Models\Facilitador;
use App\Models\CatCancelacion;
use Masmerise\Toaster\Toaster;

class PropuestaMediacion extends Component
{
    public $solicitud_id;
    public $cat_cancelaciones;
    public $facilitadores;
    
    public $cja = [
        'propuesta_inicio_fecha' => null,
        'propuesta_inicio_hora'  => null,
        'acepta_inicio'          => null,
        'fecha_vencimiento'      => null,
        'fecha_propuesta'        => null,
        'acepta_inicio_inv'      => null,
        // Mediación -------------------
        'mediacion_fecha_inicio' => null, 
        'mediacion_hora_inicio'  => null,
        'mediacion_fecha_termino'=> null,
        'mediacion_hora_termino' => null,
        'fecha_envio_archivo'    => null,
        'conclucion_id'          => null,
        'persona_concluye_id'    => null,
    ];

   public function mount($solicitud)
    {
        $this->solicitud_id = $solicitud->id;
    }


    /** ==================================================
     *  GUARDAR / ACTUALIZAR PROPUESTA
     * ================================================== */
    public function guardarPropuesta()
    {
        // Validar si hay al menos un dato lleno
        if (collect($this->cja)->filter()->isNotEmpty()) {

            Cja::updateOrCreate(
                ['solicitud_id' => $this->solicitud_id],
                $this->cja
            );

            Toaster::success('Propuesta guardada correctamente.');
        } else {
            Toaster::warning('No hay datos para guardar.');
        }
    }

    public function render()
    {
        return view('livewire.solicitud.propuesta-mediacion');
    }
}
