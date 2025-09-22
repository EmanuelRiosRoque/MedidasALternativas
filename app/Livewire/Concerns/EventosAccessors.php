<?php

namespace App\Livewire\Concerns;

use Carbon\Carbon;

trait EventosAccessors
{
    public function getEvEtapaProperty(): ?object
    {
        return $this->esEtapa2 ? ($this->eventoMediacion ?? null) : ($this->evento ?? null);
    }

    public function getFechaAtEventoProperty(): ?string
    {
        return $this->evEtapa->fecha ?? null;
    }

    public function getOpcionSeparadosProperty(): bool
    {
        return (int)($this->evEtapa->opcion_invitacion ?? 1) === 0; // 0 => separados
    }

    public function eventoParaInv($inv): ?object
    {
        if ($inv->evento) return $inv->evento;

        if ($this->isPreMediacion) {
            if ($inv->numero_inv == 1) return $this->evento ?? null;
            if ($inv->numero_inv == 2) return $this->eventoSegPreMedicion ?? ($this->evento ?? null);
            return $this->evento ?? null;
        }
        return $this->eventoMediacion ?? null;
    }

    public function fmtHora(?string $t): string
    {
        return $t ? Carbon::parse($t)->format('H:i') : '—';
    }
}
