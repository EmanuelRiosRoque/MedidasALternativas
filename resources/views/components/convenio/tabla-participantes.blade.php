@props(['solicitantes', 'heading', 'tipo'])

<h2 class="text-lg font-semibold text-neutral-800 dark:text-white py-2">{{ $heading }}</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">
    @foreach ($solicitantes as $index => $solicitante)
        <div class="relative group">
            {{-- Botón de eliminar --}}
            <button
                wire:click.stop="eliminarPersona({{ $index }}, '{{ $tipo }}')"
                class="absolute top-2 right-2 text-red-500 hover:text-red-400 text-xl font-bold z-10 cursor-pointer"
                title="Eliminar"
            >
                ×
            </button>

            {{-- Card del solicitante --}}
            <div
                wire:click="seleccionarPersona({{ $index }}, '{{ $tipo }}')"
                class="cursor-pointer bg-neutral-900 border border-neutral-700 rounded-2xl p-5 shadow-md 
                       transition-all duration-300 ease-in-out 
                       hover:shadow-2xl hover:ring-2 hover:ring-emerald-500 hover:scale-[1.01]"
            >
                <p class="text-base font-semibold text-neutral-100 mb-1">
                    {{ $solicitante['persona'] === 'moral' ? $solicitante['razon_social'] : $solicitante['nombre'] }}
                </p>

                @if ($solicitante['persona'] === 'moral')
                    <p class="text-sm text-neutral-400"><strong>RFC:</strong> {{ $solicitante['rfc'] }}</p>
                    <p class="text-sm text-neutral-400"><strong>Teléfono:</strong> {{ $solicitante['telefono'] }}</p>
                @else
                    <p class="text-sm text-neutral-400"><strong>Edad:</strong> {{ $solicitante['edad'] }}</p>
                    <p class="text-sm text-neutral-400"><strong>Sexo:</strong> {{ $solicitante['sexo'] }}</p>
                @endif

                <p class="text-sm text-neutral-400"><strong>Municipio:</strong> {{ $solicitante['municipio'] }}</p>
            </div>
        </div>
    @endforeach
</div>
