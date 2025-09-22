<?php

namespace App\Livewire\Concerns;

trait UiText
{
    public function getTituloHeaderProperty(): ?string
    {
        if ($this->mostrarPreguntaAceptacion) return 'Resultado de pre-mediación';
        if ($this->bloqueoNueva) return null; // ocultar título si está bloqueado
        return $this->isPrimera
            ? "Crear primera {$this->etqUnidadSing}"
            : "Crear nueva {$this->etqUnidadSing} (#{$this->next})";
    }

    public function getAccionClickProperty(): string
    {
        return $this->isPrimera ? 'primera' : 'nueva';
    }

    public function getTextoBtnProperty(): string
    {
        return $this->isPreMediacion
            ? ($this->isPrimera ? "Enviar invitación" : "Enviar invitación #{$this->next}")
            : ($this->isPrimera ? "Crear sesión" : "Crear sesión #{$this->next}");
    }

    public function getChipProcesoProperty(): array
    {
        return $this->isPreMediacion
            ? ['label' => "Pre-mediación • máx. {$this->maxInvPre}", 'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300']
            : ['label' => "{$this->etiquetaEtapa2} • máx. {$this->maxInvEtapa2}", 'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'];
    }

    public function getChipAsistenciaProperty(): ?array
    {
        if ($this->isPrimera || !$this->ultima) return null;

        if (is_null($this->ultima->asistio)) {
            return ['label' => 'Asistencia: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300'];
        }
        return $this->ultima->asistio
            ? ['label' => 'Asistencia: Sí', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => 'Asistencia: No', 'class' => 'bg-red-100 text-red-700 dark:bg-red-700/20 dark:text-red-300'];
    }

    public function getChipAceptoProperty(): ?array
    {
        if ($this->isPrimera || !$this->ultima || $this->ultima->asistio !== 1) return null;

        if (is_null($this->ultima->acepta_proceso)) {
            return ['label' => ($this->isPreMediacion ? 'Aceptación: pendiente' : 'Acuerdo: pendiente'), 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300'];
        }

        return $this->ultima->acepta_proceso
            ? ['label' => ($this->isPreMediacion ? 'Aceptó mediación' : 'Con acuerdo'), 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => ($this->isPreMediacion ? 'No aceptó mediación' : 'Sin acuerdo'), 'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300'];
    }

    public function getTooltipMsgProperty(): string
    {
        if ($this->mostrarPreguntaAceptacion) return 'Primero registra la aceptación / rechazo.';
        if ($this->bloqueoAsistencia)        return 'Primero registra la asistencia.';
        return $this->motivoBloqueo ?: '';
    }
}
