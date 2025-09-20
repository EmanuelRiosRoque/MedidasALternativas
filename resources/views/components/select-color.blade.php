@props([
  // v-model de Livewire
  'model' => 'colorEvento',
  // ancho como clase Tailwind (ej. w-64). Si no pasas nada: w-40
  'widthClass' => null,
  // ancho en px (ej. 260). Si lo pasas, manda sobre widthClass
  'widthPx' => null,
])

<div
  x-data="{
    open: false,
    opciones: {
      'bg-sky-300 dark:bg-sky-600': 'Azul cielo',
      'bg-emerald-300 dark:bg-emerald-600': 'Verde',
      'bg-rose-300 dark:bg-rose-600': 'Rosa',
      'bg-purple-300 dark:bg-purple-600': 'Morado',
      'bg-amber-300 dark:bg-amber-600': 'Ámbar',
      'bg-orange-300 dark:bg-orange-600': 'Naranja',
      'bg-cyan-300 dark:bg-cyan-600': 'Cian',
      'bg-violet-300 dark:bg-violet-600': 'Lila',
      'bg-lime-300 dark:bg-lime-600': 'Lima',
      'bg-fuchsia-300 dark:bg-fuchsia-600': 'Fucsia',
    },
    valor: @entangle($model),
    get label() {
      return this.valor && this.opciones[this.valor] ? this.opciones[this.valor] : 'Elige un color'
    },
    selectColor(v) { this.valor = v; this.open = false; },
  }"

  {{-- El usuario puede pasar más clases. Su `class` se fusiona al final. --}}
  {{ $attributes->class([
      'relative',
      $widthClass ?: 'w-40',
    ])
    ->merge([
      'style' => $widthPx ? "width: {$widthPx}px;" : null
    ])
  }}
  @keydown.escape.window="open=false"
>
  {{-- Botón --}}
  <button type="button" @click="open = !open" :aria-expanded="open.toString()"
    class="w-full flex items-center justify-between px-4 py-2 border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-600 text-sm rounded-md shadow-sm">
    <div class="flex items-center gap-2">
      <span class="w-4 h-4 rounded-full border border-gray-400" :class="valor || 'bg-gray-300'"></span>
      <span x-text="label"></span>
    </div>
    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  {{-- Backdrop para cerrar al hacer clic fuera --}}
  <div x-show="open" x-cloak class="fixed inset-0 z-10" @click="open=false"></div>

  {{-- Panel (hereda ancho del wrapper) --}}
  <div x-show="open" x-cloak
       class="absolute z-20 mt-2 w-full bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-md shadow-lg p-2">
    @foreach([
      'bg-sky-300 dark:bg-sky-600' => 'Azul cielo',
      'bg-emerald-300 dark:bg-emerald-600' => 'Verde',
      'bg-rose-300 dark:bg-rose-600' => 'Rosa',
      'bg-purple-300 dark:bg-purple-600' => 'Morado',
      'bg-amber-300 dark:bg-amber-600' => 'Ámbar',
      'bg-orange-300 dark:bg-orange-600' => 'Naranja',
      'bg-cyan-300 dark:bg-cyan-600' => 'Cian',
      'bg-violet-300 dark:bg-violet-600' => 'Lila',
      'bg-lime-300 dark:bg-lime-600' => 'Lima',
      'bg-fuchsia-300 dark:bg-fuchsia-600' => 'Fucsia',
    ] as $value => $label)
      <div @click="selectColor('{{ $value }}')"
           class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-700 rounded">
        <span class="w-4 h-4 rounded-full {{ $value }} border border-gray-400"></span>
        <span>{{ $label }}</span>
      </div>
    @endforeach
  </div>
</div>
