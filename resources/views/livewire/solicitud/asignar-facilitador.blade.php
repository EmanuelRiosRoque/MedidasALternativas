{{-- ========================= CONTENEDOR PRINCIPAL ========================= --}}
<div>
  <x-solicitud.section-header title="Detalles de la Solicitud">
    Revisa los datos capturados, asigna facilitador y consulta a los participantes. <br>
    <p class="text-emerald-600 font-bold">{{ $solicitud->folio_materia }}</p>
    <p class="text-emerald-600 font-bold">
      {{ $solicitud->modalidad == 1 ? 'Presencial' : 'En Linea' }}
    </p>
  </x-solicitud.section-header>

  <div class="max-w-7xl mx-auto space-y-10 mt-4">

    {{-- ========================= SECCIÓN MATERIA ========================= --}}
    @if ($solicitud->materia != 'familiar')
    <div class="flex flex-col items-start text-sm text-neutral-600 dark:text-neutral-300 font-semibold space-y-1">
      <span>Materia:</span>
      <flux:radio.group wire:model.change="materia">
        <flux:radio value="civil" label="Civil" />
        <flux:radio value="mercantil" label="Mercantil" />
      </flux:radio.group>
    </div>
    <hr class="border-neutral-200 dark:border-neutral-700 my-4">
    @endif

    {{-- ========================= FILA 1: FACILITADORES ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">

      {{-- ======= FACILITADOR (SOLICITANTES) ======= --}}
      <section
        class="rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-md p-8 flex flex-col items-center justify-center transition duration-300 ease-in-out hover:shadow-2xl">
        <div class="h-16 w-16 rounded-full flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
          <flux:icon.user class="h-8 w-8 text-neutral-400 dark:text-neutral-300" />
        </div>

        <h2 class="mt-3 text-xs font-semibold tracking-wide text-neutral-500 dark:text-neutral-400 uppercase">
          Facilitador (Solicitantes)
        </h2>

        <p class="mt-2 text-lg font-semibold text-neutral-900 dark:text-white text-center">
          {{ $solicitud->facilitadorSolicitante->nombre ?? 'Sin asignar' }}
        </p>

        <div class="mt-5">
          <flux:modal.trigger name="asignar-facilitador-solicitante">
            <flux:button size="sm" variant="primary" icon="user-plus" aria-label="Asignar facilitador solicitante">
              {{ $solicitud->facilitadorSolicitante ? 'Cambiar facilitador solicitante' : 'Agregar facilitador
              solicitante' }}
            </flux:button>
          </flux:modal.trigger>
        </div>
      </section>

      {{-- ======= FACILITADOR (INVITADOS) ======= --}}
      <section
        class="rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-md p-8 flex flex-col items-center justify-center transition duration-300 ease-in-out hover:shadow-2xl">
        <div class="h-16 w-16 rounded-full flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
          <flux:icon.users class="h-8 w-8 text-neutral-400 dark:text-neutral-300" />
        </div>

        <h2 class="mt-3 text-xs font-semibold tracking-wide text-neutral-500 dark:text-neutral-400 uppercase">
          Facilitador (Invitados)
        </h2>

        <p class="mt-2 text-lg font-semibold text-neutral-900 dark:text-white text-center">
          {{ $solicitud->facilitadorInvitado->nombre ?? 'Sin asignar' }}
        </p>

        <div class="mt-5">
          <flux:modal.trigger name="asignar-facilitador-invitado">
            <flux:button size="sm" variant="danger" icon="user-plus" aria-label="Asignar facilitador invitado">
              {{ $solicitud->facilitadorInvitado ? 'Cambiar facilitador invitado' : 'Agregar facilitador invitado' }}
            </flux:button>
          </flux:modal.trigger>
        </div>
      </section>
    </div>

    {{-- ========================= CO-MEDIADOR ========================= --}}
    <div class="mt-6">
      @if ($solicitud->coMediador)
      <div
        class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 ring-1 ring-emerald-200 dark:ring-emerald-800 shadow-sm p-6">
        <p class="text-[11px] uppercase tracking-wide text-emerald-700 dark:text-emerald-300 font-semibold">
          Co-mediador asignado
        </p>
        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
          {{ $solicitud->coMediador->nombre }}
        </p>

        <div class="mt-4">
          <flux:modal.trigger name="co-mediador">
            <flux:button size="sm" variant="ghost" icon="user-pen">Cambiar co-mediador</flux:button>
          </flux:modal.trigger>
        </div>
      </div>
      @elseif ($solicitud->tipo_proceso_id == 2)
      <div class="flex justify-end">
        <flux:modal.trigger name="co-mediador">
          <flux:button size="sm" variant="ghost" icon="user-plus">Agregar co-mediador</flux:button>
        </flux:modal.trigger>
      </div>
      @endif
    </div>

    {{-- ========================= FILA 2: LISTAS ========================= --}}
    <div class="flex justify-end mt-10">
      <a href="{{ route('solicitud.personas', ['solicitudId' => $solicitudId]) }}" class="shrink-0">
        <flux:button size="sm" variant="primary">
          <span class="inline-flex items-center gap-2">
            Ver detalle de involucrados
            <flux:icon.chevron-right class="size-4" />
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

  @include('livewire.solicitud.modal')
</div>