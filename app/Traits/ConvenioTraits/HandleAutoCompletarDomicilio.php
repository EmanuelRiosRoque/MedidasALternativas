<?php

namespace App\Traits\ConvenioTraits;

use App\Models\SepomexColonia;

trait HandleAutoCompletarDomicilio
{
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
}
