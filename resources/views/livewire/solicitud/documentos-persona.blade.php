<div>
    <h1 class="text-base font-semibold uppercase text-emerald-400 tracking-wider mb-4">
        Documentos de la persona
    </h1>

    <div class="grid grid-cols-2 gap-4">
        @php
            $nombres = [
                'identificacion' => 'Identificación',
                'formato_privacidad' => 'Formato de Privacidad',
            ];
        @endphp

        @foreach ($documentos as $doc)
            <div class="mb-4">
                <p class="text-white font-semibold">
                    {{ $nombres[$doc->tipo] ?? ucwords(str_replace('_', ' ', $doc->tipo)) }}
                </p>

                <iframe src="{{ asset('storage/documentos/' . basename($doc->ruta)) }}"
                        class="w-full h-64 rounded border border-gray-300 mb-2"
                        frameborder="0"></iframe>

                <div class="flex items-center gap-2">
                    <input type="file"
                            wire:model="nuevos.{{ $doc->tipo }}"
                            accept="application/pdf"
                            class="block w-full text-sm text-white file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0 file:text-sm file:font-semibold
                                file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer"
                            title="Elige documento"
                        />


                        <flux:button
                            variant="primary"
                            wire:click="actualizar('{{ $doc->tipo }}')"
                            wire:loading.attr="disabled"
                            wire:target="nuevos.{{ $doc->tipo }}"
                        >
                            <span wire:loading wire:target="nuevos.{{ $doc->tipo }}">Cargando documento...</span>
                            <span wire:loading.remove wire:target="nuevos.{{ $doc->tipo }}">Reemplazar</span>
                        </flux:button>
                </div>

            </div>
        @endforeach
    </div>
</div>
