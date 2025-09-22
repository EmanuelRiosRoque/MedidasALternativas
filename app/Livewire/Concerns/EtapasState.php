<?php

namespace App\Livewire\Concerns;

trait EtapasState
{
    public function getEtiquetaEtapa2Property(): string
    {
        return $this->solicitud->etapa2_alias
            ?? ($this->solicitud->tipoProcesoEtapa2->nombre ?? 'Mediación');
    }

    public function getCurrentProperty(): int
    {
        return (int) ($this->tipoProcesoId ?? 0);
    }

    public function getIsPreMediacionProperty(): bool
    {
        return $this->current === 1;
    }

    public function getEsEtapa2Property(): bool
    {
        return $this->current === 2;
    }

    public function getEtqUnidadSingProperty(): string
    {
        return $this->isPreMediacion ? 'invitación' : 'sesión';
    }

    public function getEtqUnidadPluralProperty(): string
    {
        return $this->isPreMediacion ? 'invitaciones' : 'sesiones';
    }

    public function getStepsProperty(): array
    {
        return [1 => 'Pre-mediación', 2 => $this->etiquetaEtapa2];
    }
}
