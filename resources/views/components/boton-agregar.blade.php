@props([
    'wireClick' => null, // para usar wire:click
    'texto' => 'Agregar',
    'color' => 'emerald', // 'blue', 'red', etc.
])

<button
    type="button"
    {{ $wireClick ? "wire:click=$wireClick" : '' }}
    {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center px-4 py-2 rounded-md text-white text-sm font-medium
                    " . "bg-$color-600 hover:bg-$color-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-$color-500
                    disabled:opacity-50 disabled:cursor-not-allowed"
    ]) }}
>
    {{ $texto }}
</button>
