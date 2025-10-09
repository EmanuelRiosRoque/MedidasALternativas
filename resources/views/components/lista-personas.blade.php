<div class="w-full flex justify-center  bg-white dark:bg-neutral-900">
  <div
    class="w-full max-w-7xl sm:max-w-2xl bg-white dark:bg-neutral-800 rounded-2xl shadow-md border border-neutral-200/80 dark:border-neutral-700/70
           transition duration-300 ease-in-out hover:shadow-lg hover:border-emerald-500/60 focus-within:border-emerald-500/70">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 px-5 sm:px-6 py-4 sm:py-5 border-b border-neutral-200/80 dark:border-neutral-700/60">
      <div class="flex items-center gap-3 min-w-0">
        <h3 class="text-lg sm:text-xl font-semibold tracking-tight text-neutral-900 dark:text-white truncate">
          Lista {{ $titulo }}
        </h3>

        {{-- Contador --}}
        <span
          class="shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                 bg-neutral-100 text-neutral-700 ring-1 ring-neutral-200
                 dark:bg-neutral-700/40 dark:text-neutral-100 dark:ring-neutral-600/50">
          {{ count($personas) }}
        </span>
      </div>
    </div>

    {{-- Listado --}}
    <div class="p-4 sm:p-6">
      <ul role="list" class="divide-y divide-neutral-200 dark:divide-neutral-700 rounded-xl overflow-hidden">
        @forelse ($personas as $persona)
          <li class="group">
            <div
              class="flex items-center gap-4 px-2 sm:px-3 py-3 sm:py-4 rounded-xl
                     transition-colors duration-200 ease-in-out
                     hover:bg-neutral-50 dark:hover:bg-neutral-700/30 focus-within:bg-neutral-50 dark:focus-within:bg-neutral-700/30">

              {{-- Avatar/Ícono --}}
              <div
                class="flex h-10 w-10 items-center justify-center rounded-full
                       bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                       dark:bg-emerald-400/15 dark:text-emerald-200 dark:ring-emerald-500/30">
                <flux:icon.user-circle class="size-5" />
              </div>

              {{-- Datos --}}
              <div class="min-w-0 flex-1">
                <p class="text-sm sm:text-[15px] font-medium text-neutral-900 dark:text-white truncate">
                  @if ($persona->persona === 'fisica' || $persona->persona === 'familiar')
                    {{ trim(($persona->nombre ?? '').' '.($persona->apellido_p ?? '').' '.($persona->apellido_m ?? '')) ?: 'Sin nombre' }}
                  @else
                    {{ $persona->razon_social ?? 'Sin razón social' }}
                  @endif
                </p>

                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 truncate">
                  {{ $persona->correos->first()?->email ?? 'Sin correo' }}
                </p>
              </div>

              {{-- Chip de tipo (discreto) --}}
              <span
                class="ml-auto hidden sm:inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                       bg-neutral-100 text-neutral-700 ring-1 ring-neutral-200
                       dark:bg-neutral-700/40 dark:text-neutral-100 dark:ring-neutral-600/50">
                {{ ucfirst($persona->persona ?? 'N/D') }}
              </span>
            </div>
          </li>
        @empty
          <li class="px-2 sm:px-3 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
            No hay personas registradas.
          </li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
