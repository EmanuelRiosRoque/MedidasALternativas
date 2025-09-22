<?php

namespace App\Livewire\Concerns;

trait InvCounters
{
    public function getIsPrimeraProperty(): bool
    {
        return $this->invitaciones->isEmpty();
    }

    public function getNextProperty(): int
    {
        return (int) (($this->invitaciones->max('numero_inv') ?? 0) + 1);
    }

    public function getUltimaProperty()
    {
        return $this->invitaciones->sortByDesc('numero_inv')->first();
    }
}
