{{-- Select del tipo --}}
@if ($materia === "mercantil")    
    <div class="grid grid-cols-2 gap-2 animate__animated animate__fadeIn">
        <flux:select wire:model.live="tipo" placeholder="Selecciona un tema de mediación civil">
            @foreach($tiposDisponibles as $tipoItem)
                <flux:select.option>{{ $tipoItem }}</flux:select.option>
            @endforeach
        </flux:select>
        
        {{-- Mostrar documentos según tipo --}}
        @if (!empty($documentosOpcionales))
            <flux:select wire:model.live="documentoSeleccionado" placeholder="Selecciona un documento">
                @foreach($documentosOpcionales as $doc)
                    @if (!in_array($doc, $documentosCargados))
                        <flux:select.option>{{ $doc }}</flux:select.option>
                    @endif
                @endforeach
            </flux:select>
        @endif
    </div>

    {{-- Iterar los documentos ya seleccionados con su Dropzone --}}
    @if (!empty($documentosCargados))
        @foreach($documentosCargados as $index => $doc)
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 m-2">
                    {{ $doc }}
                </label>

                {{-- Botón eliminar --}}
                <button 
                    wire:click="eliminarDocumento('{{ $doc }}')" 
                    type="button"
                    class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition"
                    title="Eliminar documento"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <livewire:dropzone 
                    wire:model="archivosSubidos.{{ $index }}" 
                    :rules="['mimes:pdf', 'max:10240']" 
                    :multiple="false"
                    :key="$doc . '-' . $index" 
                />
            </div>
        @endforeach
    @endif
    {{-- @if (!empty($documentosCargados))
        <div class="mt-6">
            <button wire:click="guardarArchivos"
                class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                Guardar documentos
            </button>
        </div>
    @endif --}}
@endif


@if ($materia === "familiar")
    <div class="grid grid-cols-2 gap-2 animate__animated animate__fadeIn">
        <flux:select wire:model.live="temaFamiliar" placeholder="Selecciona un tema de mediación familiar">
            @foreach($temasFamiliaresDisponibles as $tema)
                <flux:select.option>{{ $tema }}</flux:select.option>
            @endforeach
        </flux:select>

        @if (!empty($documentosFamiliarOpcionales))
            <flux:select wire:model.live="documentosFamiliarSeleccionado" placeholder="Selecciona un documento">
                @foreach($documentosFamiliarOpcionales as $doc)
                    @if (!in_array($doc, $documentosFamiliaresCargados))
                        <flux:select.option>{{ $doc }}</flux:select.option>
                    @endif
                @endforeach
            </flux:select>
        @endif
    </div>

    @if (!empty($documentosFamiliaresCargados))
        @foreach($documentosFamiliaresCargados as $index => $doc)
            <div class="relative mb-4">
                {{-- Etiqueta del documento --}}
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 m-2">
                    {{ $doc }}
                </label>

                {{-- Botón eliminar --}}
                <button 
                    wire:click="eliminarDocumentoFamiliar('{{ $doc }}')" 
                    type="button"
                    class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition"
                    title="Eliminar documento"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Dropzone sin cambios --}}
                <livewire:dropzone 
                    wire:model="archivosFamiliaresSubidos.{{ $index }}" 
                    :rules="['mimes:pdf','max:10240']" 
                    :multiple="false" 
                    :key="$doc . '-' . $index" 
                />
            </div>
        @endforeach
    @endif
@endif



