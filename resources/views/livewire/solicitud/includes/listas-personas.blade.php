{{-- CONTENEDOR --}}
<div class="max-w-7xl mx-auto space-y-8 mt-2">

  {{-- FILA 1: FACILITADORES (2 columnas en md+) --}}
  @php
      // Co-mediador
      $coNombre = null;
      $coId = $solicitud->co_mediador_id ?? null;

      if ($solicitud->coMediador ?? null) {
          $coNombre = $solicitud->coMediador->nombre;
          $coId = $solicitud->co_mediador_id ?? $solicitud->coMediador->id ?? $coId;
      } elseif ($solicitud->co_mediador ?? null) {
          $coNombre = $solicitud->co_mediador->nombre;
          $coId = $solicitud->co_mediador_id ?? $solicitud->co_mediador->id ?? $coId;
      }

      // Facilitadores actuales (soporta camelCase y snake_case)
      $facSolicitanteNombre = $solicitud->facilitadorSolicitante->nombre
          ?? $solicitud->facilitador_solicitante->nombre
          ?? null;

      $facInvitadoNombre = $solicitud->facilitadorInvitado->nombre
          ?? $solicitud->facilitador_invitado->nombre
          ?? null;

      $tieneFacSolicitante = (bool) ($solicitud->facilitador_solicitante_id
          ?? $solicitud->facilitadorSolicitante->id
          ?? null);

      $tieneFacInvitado = (bool) ($solicitud->facilitador_invitado_id
          ?? $solicitud->facilitadorInvitado->id
          ?? null);

      // clase base para las tarjetas
      $card = 'rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-xl p-6';
  @endphp

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

    {{-- TARJETA: FACILITADOR (SOLICITANTES) --}}
    <section class="{{ $card }} flex flex-col items-center min-h-[220px]">
      <div class="h-16 w-16 rounded-full flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-8 w-8 text-neutral-400 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="1.5" d="M16 7a4 4 0 1 1-8 0a4 4 0 0 1 8 0M4 20a8 8 0 1 1 16 0"/>
        </svg>
      </div>

      <h3 class="mt-3 text-xs font-semibold tracking-wide text-neutral-500 dark:text-neutral-400 uppercase">
        Facilitador (Solicitantes)
      </h3>
      <p class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white text-center">
        {{ $facSolicitanteNombre ?? 'Sin asignar' }}
      </p>

      @unless($tieneFacSolicitante)
        <div class="mt-4">
          <flux:modal.trigger name="asignar-facilitador-solicitante">
            <flux:button variant="primary" icon="user-plus">Agregar facilitador solicitante</flux:button>
          </flux:modal.trigger>
        </div>

        {{-- Modal: asignar facilitador solicitante --}}
        <flux:modal name="asignar-facilitador-solicitante" class="w-full max-w-md">
          <div class="space-y-6">
            <div>
              <flux:heading size="lg">Elige al facilitador (Solicitantes)</flux:heading>
              <flux:text class="mt-2">Selecciona quién facilitará la parte solicitante.</flux:text>
            </div>

            <flux:select wire:model="facilitadorSolicitanteId" placeholder="Seleccione un facilitador...">
              @forelse ($facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
              @empty
                <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
              @endforelse
            </flux:select>
            @error('facilitadorSolicitanteId')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex">
              <flux:spacer />
              <flux:button wire:click="guardarFacilitadorSolicitante" variant="primary">Guardar</flux:button>
            </div>
          </div>
        </flux:modal>
      @endunless
    </section>

    {{-- TARJETA: FACILITADOR (INVITADOS) --}}
    <section class="{{ $card }} flex flex-col items-center min-h-[220px]">
      <div class="h-16 w-16 rounded-full flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-8 w-8 text-neutral-400 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="1.5" d="M16 7a4 4 0 1 1-8 0a4 4 0 0 1 8 0M4 20a8 8 0 1 1 16 0"/>
        </svg>
      </div>

      <h3 class="mt-3 text-xs font-semibold tracking-wide text-neutral-500 dark:text-neutral-400 uppercase">
        Facilitador (Invitados)
      </h3>
      <p class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white text-center">
        {{ $facInvitadoNombre ?? 'Sin asignar' }}
      </p>

      @unless($tieneFacInvitado)
        <div class="mt-4">
          <flux:modal.trigger name="asignar-facilitador-invitado">
            <flux:button variant="danger" icon="user-plus">Agregar facilitador invitado</flux:button>
          </flux:modal.trigger>
        </div>

        {{-- Modal: asignar facilitador invitado --}}
        <flux:modal name="asignar-facilitador-invitado" class="w-full max-w-md">
          <div class="space-y-6">
            <div>
              <flux:heading size="lg">Elige al facilitador (Invitados)</flux:heading>
              <flux:text class="mt-2">Selecciona quién facilitará la parte invitada.</flux:text>
            </div>

            <flux:select wire:model="facilitadorInvitadoId" placeholder="Seleccione un facilitador...">
              @forelse ($facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
              @empty
                <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
              @endforelse
            </flux:select>
            @error('facilitadorInvitadoId')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex">
              <flux:spacer />
              <flux:button wire:click="guardarFacilitadorInvitado" variant="primary">Guardar</flux:button>
            </div>
          </div>
        </flux:modal>
      @endunless
    </section>

    {{-- BLOQUE CO-MEDIADOR (ocupa el ancho de las dos columnas cuando aplica) --}}
    @if ($coId)
      <div class="md:col-span-2 {{ $card }} bg-emerald-50/60 dark:bg-emerald-900/10 ring-emerald-200 dark:ring-emerald-700/40">
        <p class="text-[11px] uppercase tracking-wide text-emerald-700 dark:text-emerald-300 font-semibold">
          Co-mediador asignado
        </p>
        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $coNombre }}</p>
      </div>
    @elseif ($solicitud->tipo_proceso_id == 2)
      <div class="md:col-span-2 flex justify-end">
        <flux:modal.trigger name="co-mediador">
          <flux:button variant="ghost">Agregar co-mediador</flux:button>
        </flux:modal.trigger>
      </div>

      {{-- Modal co-mediador --}}
      <flux:modal name="co-mediador" class="w-full max-w-md">
        <div class="space-y-6">
          <div>
            <flux:heading size="lg">Elige al co-mediador</flux:heading>
            <flux:text class="mt-2">Selecciona un facilitador para asignarlo como co-mediador.</flux:text>
          </div>

          <flux:select wire:model="coMediadorId" placeholder="Seleccione un co-mediador...">
            @forelse ($facilitadores as $fac)
              <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
            @empty
              <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
            @endforelse
          </flux:select>
          @error('coMediadorId')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror

          <div class="flex">
            <flux:spacer />
            <flux:button wire:click="guardarCoMediador" variant="primary">Guardar</flux:button>
          </div>
        </div>
      </flux:modal>
    @endif
  </div>

  {{-- FILA 2: LISTAS (2 columnas en md+) --}}
  <div class=" flex justify-end">
      <a href="{{ route('solicitud.personas', ['solicitudId' => $solicitudId]) }}"
         class="shrink-0">
        <flux:button variant="primary">
          <span class="inline-flex items-center gap-2">
            Ver Solicitantes
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
              <path d="M5 12h14" />
              <path d="M12 5l7 7-7 7" />
            </svg>
          </span>
        </flux:button>
      </a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
    <div class="h-full">
      <x-lista-personas :$solicitudId :personas="$solicitantes" titulo="Solicitantes" />
    </div>
    <div class="h-full">
      <x-lista-personas :$solicitudId :personas="$invitados" titulo="Invitados" />
    </div>
  </div>

</div>
