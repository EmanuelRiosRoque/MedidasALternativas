<div x-data="{
                    open: false,
                    colorTemp: $wire.entangle('colorEvento'),
                    selectColor(color) {
                        this.colorTemp = color;
                        this.open = false;
                    }
                }" class="relative w-40">
    <button type="button" @click="open = !open"
        class="w-full flex items-center justify-between px-4 py-2 border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-600 text-sm rounded-md shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-4 h-4 rounded-full" :class="colorTemp || 'bg-gray-300'"></span>
            <span x-text="colorTemp
                            ? colorTemp.split(' ')[0].replace('bg-', '').replace('-100', '').replace('-800', '').replace('-', ' ')
                            : 'Elige un color'">
            </span>
        </div>
        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false"
        class="absolute z-10 mt-2 w-full bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-md shadow-lg p-2">
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