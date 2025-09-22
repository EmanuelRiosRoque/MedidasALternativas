<?php

namespace App\Livewire\Concerns;

trait Bloqueos
{
    // Puedes mover estos límites a config/mediacion.php si quieres
    public int $maxInvPre = 2;
    public int $maxInvEtapa2 = 10;

    public function getEsSegundaPreProperty(): bool
    {
        return $this->isPreMediacion && $this->next == 2;
    }

    public function getBloqueoLimitePreProperty(): bool
    {
        return $this->isPreMediacion && $this->next > $this->maxInvPre;
    }

    public function getBloqueoLimiteEtp2Property(): bool
    {
        return $this->esEtapa2 && $this->next > $this->maxInvEtapa2;
    }

    public function getBloqueoAsistenciaProperty(): bool
    {
        return ($this->ultima && is_null($this->ultima->asistio));
    }

    public function getRequiereAceptarAntesProperty(): bool
    {
        return $this->isPreMediacion
            && $this->ultima
            && $this->ultima->asistio === 1
            && is_null($this->ultima->acepta_proceso);
    }

    public function getBloqueoPorAceptoProperty(): bool
    {
        return $this->isPreMediacion
            && $this->ultima
            && $this->ultima->asistio === 1
            && (int)$this->ultima->acepta_proceso === 1;
    }

    public function getBloqueoReasignacionSegPreProperty(): bool
    {
        if (!$this->esSegundaPre) return false;
        if (!$this->ultima) return true;

        if ((int)$this->ultima->acepta_proceso === 0) {
            return !$this->eventoSegPreMedicion;
        }
        return true;
    }

    public function getBloqueoPorAcuerdoProperty(): bool
    {
        return $this->esEtapa2 && ($this->ultima && (int)$this->ultima->acepta_proceso === 1);
    }

    public function getBloqueoNuevaProperty(): bool
    {
        return $this->bloqueoLimitePre
            || $this->bloqueoLimiteEtp2
            || $this->bloqueoAsistencia
            || $this->requiereAceptarAntes
            || $this->bloqueoPorAcepto
            || $this->bloqueoPorAcuerdo
            || $this->bloqueoReasignacionSegPre;
    }

    public function getMotivoBloqueoProperty(): string
    {
        if ($this->bloqueoLimitePre) {
            return "En Pre-mediación solo se permiten {$this->maxInvPre} invitaciones.";
        }
        if ($this->bloqueoLimiteEtp2) {
            return "En {$this->etiquetaEtapa2} solo se permiten {$this->maxInvEtapa2} sesiones.";
        }
        if ($this->bloqueoAsistencia) {
            return "Primero registra la asistencia de la última " . ($this->isPreMediacion ? 'invitación' : 'sesión') . ".";
        }
        if ($this->requiereAceptarAntes) {
            return 'Confirma si aceptaron mediación antes de crear otra invitación.';
        }
        if ($this->bloqueoPorAcepto) {
            return 'Ya aceptaron mediación. No se permiten nuevas invitaciones.';
        }
        if ($this->bloqueoPorAcuerdo) {
            return 'Ya hubo convenio/acuerdo. No se permiten nuevas sesiones.';
        }
        if ($this->bloqueoReasignacionSegPre) {
            if ($this->esSegundaPre && $this->ultima && (int)$this->ultima->acepta_proceso === 0) {
                return 'Debe existir un evento de reasignación para habilitar la 2ª invitación de Pre-mediación.';
            }
            return 'La 2ª invitación solo procede cuando NO aceptó mediación.';
        }
        return '';
    }

    public function getMostrarPreguntaAceptacionProperty(): bool
    {
        return $this->isPreMediacion
            && $this->ultima
            && $this->ultima->asistio === 1
            && is_null($this->ultima->acepta_proceso);
    }
}
