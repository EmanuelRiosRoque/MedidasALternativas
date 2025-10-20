<div class="p-6">
  <div class="rounded-xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-md p-4 space-y-5">

    <h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100 flex items-center gap-2">
      <flux:icon.calendar class="size-4 text-emerald-500" />
      Datos de la mediación
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Columna 1 --}}
      <div class="space-y-4">
        {{-- Fecha de inicio --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Fecha de inicio
          </label>
          <flux:input type="date" wire:model.defer="cja.mediacion_fecha_inicio" />
        </div>

        {{-- Hora de inicio --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Hora de inicio
          </label>
          <flux:input type="time" wire:model.defer="cja.mediacion_hora_inicio" />
        </div>
      </div>

      {{-- Columna 2 --}}
      <div class="space-y-4">
        {{-- Fecha de término --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Fecha de término
          </label>
          <flux:input type="date" wire:model.defer="cja.mediacion_fecha_termino" />
        </div>

        {{-- Fecha de envío archivo judicial --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Fecha de envío archivo judicial
          </label>
          <flux:input type="date" wire:model.defer="cja.fecha_envio_archivo" />
          {{-- Si prefieres capturar fecha y hora: type="datetime-local" --}}
        </div>
      </div>

      {{-- Columna 3 --}}
      <div class="space-y-4">
        {{-- Forma de concluir la mediación --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Forma de concluir la mediación
          </label>
          <flux:select placeholder="Seleccione..." wire:model.defer="cja.conclucion_id">
            @foreach ($cat_cancelaciones as $cancelacion)
              <flux:select.option value="{{ $cancelacion->id }}" label="{{ $cancelacion->motivo }}" />
            @endforeach
          </flux:select>
        </div>

        {{-- Personal que concluye la mediación --}}
        <div>
          <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
            Personal que concluye la mediación
          </label>
          <flux:select placeholder="Seleccione..." wire:model.defer="cja.persona_concluye_id">
            @foreach ($facilitadores as $facilitador)
              <flux:select.option value="{{ $facilitador->id }}" label="{{ $facilitador->nombre }}" />
            @endforeach
          </flux:select>
        </div>
      </div>
    </div>
  </div>
</div>
