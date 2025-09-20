@props([
  'mostrar' => false,
  'facilitadores' => [],
  'opcionSeparados' => false,
  'horarios' => [],
  // Texto
  'titulo' => 'Crear evento • Fecha y hora',
  // Acción (por defecto reutiliza el mismo método que reasignación)
  'wireClick' => 'crearEvento',
])

@if ($mostrar)
  <div class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
    <h3 class="text-base font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
      <flux:icon.calendar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
      {{ $titulo }}
    </h3>

    <div class="grid grid-cols-2 gap-2">
      <flux:select wire:model.defer="facilitador" placeholder="Elige facilitador disponible">
        @foreach ($facilitadores as $fac)
          <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
        @endforeach
      </flux:select>

      <x-select-color model="colorEvento" :widthPx="340" />
    </div>

    {{-- Fecha del evento --}}
    <flux:input wire:model.defer="fechaNueva" type="date" label="Fecha del evento" placeholder="Seleccione la fecha" />

    <div class="pt-2">
      @if (!$opcionSeparados)
        {{-- Horario único para ambas partes --}}
        <div class="space-y-2">
          <p class="text-sm font-semibold dark:text-white">Horario (ambas partes)</p>
          <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
            <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>
        </div>
      @else
        {{-- Horarios separados --}}
        <div class="space-y-4">
          <p class="text-sm font-semibold dark:text-white">Horario para solicitante(s)</p>
          <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
            <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>

          <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
          <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.defer="horaInicioInvitadoEvento" placeholder="Hora inicio">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
            <flux:select wire:model.defer="horaFinInvitadoEvento" placeholder="Hora fin">
              @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>
        </div>
      @endif
    </div>

    <div class="pt-2 flex items-center justify-end gap-3">
      <flux:button variant="primary" icon="calendar"
        class="bg-emerald-600 hover:bg-emerald-700 text-white"
        wire:click="{{ $wireClick }}"
        wire:loading.attr="disabled" wire:target="{{ $wireClick }}">
        Crear evento
      </flux:button>
      <span class="text-xs text-neutral-500" wire:loading wire:target="{{ $wireClick }}">Creando evento…</span>
    </div>

    <p class="text-xs text-neutral-500">
      Al crear el evento, podrás enviar la {{ strtolower($etqUnidadSing ?? 'invitación') }}.
    </p>
  </div>
@endif
