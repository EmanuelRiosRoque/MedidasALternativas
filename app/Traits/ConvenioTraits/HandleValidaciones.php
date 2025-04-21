<?php

namespace App\Traits\ConvenioTraits;

trait HandleValidaciones
{
    public function validarPaso($paso)
    {
        switch ($paso) {
            case 1:
                $this->validate([
                    'nombre' => 'required',
                    'apellido' => 'required',
                ]);
                break;
    
            case 2:
                $this->validate([
                    'solicitante.nombre' => 'required',
                    'solicitante.curp' => 'required|min:18',
                ]);
                break;
    
            case 3:
                $this->validate([
                    'invitado.nombre' => 'required',
                    'invitado.relacion' => 'required',
                ]);
                break;
    
            case 4:
                $this->validate([
                    'documentos.identificacion' => 'required',
                ]);
                break;
        }
    }

    
    public function cambiarTab($nuevoTab)
    {
        // Valida solo si está yendo hacia adelante
        if ($nuevoTab > $this->tab) {
            $this->validarPaso($this->tab);
        }

        $this->tab = $nuevoTab;
    }

}
