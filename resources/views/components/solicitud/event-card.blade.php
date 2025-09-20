@props([
  'labelFecha' => 'Fecha',
  'fecha' => null,
  'opcionSeparados' => false,
  'hIni' => null,
  'hFin' => null,
  'hIniI' => null,
  'hFinI' => null,
  'fmtHora' => null,
  'facilitador' => null,
  'titulo' => 'Detalles del evento',
])

<flux:callout color="neutral" class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
  <flux:callout.heading class="flex items-center gap-2 text-emerald-700 dark:text-emerald-300">
    <flux:icon.bell variant="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
    {{ $titulo }}
  </flux:callout.heading>
  <flux:callout.text class="space-y-3">
    @if($fecha)
      <div class="flex items-center gap-2">
        <flux:icon.calendar class="w-4 h-4 text-emerald-600" />
        <span class="font-medium">{{ $labelFecha }}:</span>
        <span class="font-semibold">{{ $fecha }}</span>
      </div>
    @endif

    @if (!$opcionSeparados)
      <div class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
        <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
        @if ($hIni && $hFin)
          <p class="text-sm font-semibold">{{ $fmtHora($hIni) }} - {{ $fmtHora($hFin) }}</p>
        @else
          <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
        @endif
      </div>
    @else
      <div class="mt-2 grid sm:grid-cols-2 gap-3">
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
          <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
          <p class="text-sm font-semibold">{{ $hIni ? $fmtHora($hIni) : '—' }} - {{ $hFin ? $fmtHora($hFin) : '—' }}</p>
        </div>
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
          <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
          <p class="text-sm font-semibold">{{ $hIniI ? $fmtHora($hIniI) : '—' }} - {{ $hFinI ? $fmtHora($hFinI) : '—' }}</p>
        </div>
      </div>
    @endif

    @if(!empty($facilitador))
      <div class="mt-2 text-xs">
        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-700/20 dark:text-emerald-300">
          Facilitador (evento): {{ $facilitador }}
        </span>
      </div>
    @endif
  </flux:callout.text>
</flux:callout>
