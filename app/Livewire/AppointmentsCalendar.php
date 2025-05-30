<?php

namespace App\Livewire;

use App\Models\Agenda;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;

class AppointmentsCalendar extends LivewireCalendar
{
    public $fechaSeleccionada = '';
    public $eventosDelDia = [];
    public bool $soloHoy = false;
    public ?Carbon $semanaActualInicio = null;
    public ?Carbon $semanaActualFin = null;
    public string|array $rolUsuario;

    /**
     * Funcion para obtener el rol del usuario.
     */
    private function obtenerRolUsuario(): array
    {
        $rol = auth()->user()->getRoleNames()->first();

        // Si el rol es 'civil', también puede ver 'mercantil'
        if ($rol === 'civil') {
            return ['civil', 'mercantil'];
        }

        return [$rol];
    }

    /**
     * Método que se ejecuta automáticamente para crear el calendario.
     */
    public function events(): Collection
    {
        $this->rolUsuario = $this->obtenerRolUsuario();

        return Agenda::with(['facilitador', 'solicitud'])
            ->where('fecha', '>=', $this->gridStartsAt->toDateString())
            ->where('fecha', '<=', $this->gridEndsAt->toDateString())
            ->whereHas('solicitud', function ($query) {
                $query->whereIn('materia', $this->rolUsuario);
            })
            ->get()
            ->map(function (Agenda $model) {
                return [
                    'id' => $model->id,
                    'title' => $model->facilitador->nombre ?? 'Sin nombre',
                    'description' => $model->solicitud->id ?? 'Sin solicitud',
                    'date' => $model->fecha . ' ' . $model->hora_inicio,
                    'hora_inicio' => $model->hora_inicio,
                    'hora_fin' => $model->hora_fin,
                    'color' => $model->color
                ];
            });
    }

    /**
     * Método que se ejecuta automáticamente al mover (drag & drop) un evento.
     */
    public function onEventDropped($eventId, $year, $month, $day)
    {
        $nuevaFecha = Carbon::createFromDate($year, $month, $day);

        // Evita mover eventos a fechas pasadas
        if ($nuevaFecha->isBefore(Carbon::today())) {
            return;
        }

        $evento = Agenda::find($eventId);

        if ($evento) {
            // Conservar la hora anterior
            $nuevaFecha->setTimeFrom(Carbon::createFromFormat('H:i:s', $evento->hora_inicio));

            // Actualizar la fecha
            $evento->fecha = $nuevaFecha->toDateString();
            $evento->save();
        }
    }

     /**
     * Método que se ejecuta automáticamente al dar click en el dia solo enviamos la fecha y ejecutamos un dispatch para 
     * que sea oida en EventModal.
     */
    public function onDayClick($year, $month, $day)
    {
        $this->fechaSeleccionada = Carbon::createFromDate($year, $month, $day)->toDateString();
        $this->dispatch('abrirModalDia', fecha: $this->fechaSeleccionada);
    }

    // Evento que abre el evento para ver las caracteristicas de ese evento
    public function onEventClick($eventId)
    {
        $this->dispatch('abrirModalEvento', id: $eventId);
    }

    // Funcion para resetear vista
    public function resetVista()
    {
        return redirect()->to(request()->header('Referer'));
    }

    // Funcion para ir a las semana actual
    public function goToWeek()
    {
        $this->soloHoy = false;
        $this->gridStartsAt = now()->startOfWeek();
        $this->gridEndsAt = now()->endOfWeek();
    }

    // Funcion para ir a dia actual
    public function goToCurrentWeek()
    {
        $this->gridStartsAt = now()->startOfWeek();
        $this->gridEndsAt = now()->endOfWeek();
        $this->soloHoy = true;
    }

    // Funcion para ir a las semana anterior
    public function goToPreviousWeek()
    {
        $this->gridStartsAt = $this->gridStartsAt->copy()->subWeek();
        $this->gridEndsAt = $this->gridEndsAt->copy()->subWeek();
    }

    // Funcion para ir a las semana siguiente
    public function goToNextWeek()
    {
        $this->gridStartsAt = $this->gridStartsAt->copy()->addWeek();
        $this->gridEndsAt = $this->gridEndsAt->copy()->addWeek();
    }
}
