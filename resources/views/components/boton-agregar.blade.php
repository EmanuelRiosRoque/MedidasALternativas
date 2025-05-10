@props([
    'wireClick' => null, // para usar wire:click
    'texto' => 'Agregar',
    'color' => 'emerald', // puedes cambiarlo a 'blue', 'red', etc.
])

<button
    type="button"
    {{ $wireClick ? "wire:click=$wireClick" : '' }}
    class="group w-10 h-10 bg-{{ $color }}-600 text-white text-sm font-medium flex items-center justify-center rounded-full transition-all duration-300 ease-in-out overflow-hidden relative hover:w-28 hover:rounded-md"
>
    <div class="flex items-center justify-center w-full h-full relative">
        <!-- Ícono + -->
        <span
            class="absolute transition-all duration-300 ease-in-out 
                    opacity-100 scale-100 group-hover:opacity-0 group-hover:scale-90"
        >+</span>

        <!-- Texto -->
        <span
            class="transition-all duration-300 ease-in-out 
                    opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100"
        >{{ $texto }}</span>
    </div>
</button>
