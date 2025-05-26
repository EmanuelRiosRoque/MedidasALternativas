<?php

namespace App\Traits\ConvenioTraits;

trait HandleArreglosLogicos
{
    public function agregarCorreo()
	{
		$correo = trim($this->correo_temp);

		if ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
			$this->correos[] = ($correo);
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
}
