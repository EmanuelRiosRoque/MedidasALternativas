<?php

namespace App\Traits\ConvenioTraits;

trait HandleUpdatedConvenio
{
    // Se ejecuta cuando se actualiza la propiedad "persona"
	public function updatedPersona()
	{
		$this->limpiarCamposPersona(preservarPersona: true);
	}
    // Se ejecuta cuando se actualiza la propiedad "materia"
	public function updatedMateria()
	{
		$this->limpiarCamposPersona(preservarPersona: false);
	}

}
