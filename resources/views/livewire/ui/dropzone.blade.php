<section class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-neutral-800">{{ $label }}</h3>
        @if($help)
            <p class="text-xs text-neutral-500">{{ $help }}</p>
        @endif
    </div>

    {{-- Área de carga / drag & drop --}}
    <div
        x-data="{ hover:false }"
        @dragover.prevent="hover=true"
        @dragleave.prevent="hover=false"
        @drop.prevent="
            hover=false;
            $refs.fileInput.files = $event.dataTransfer.files;
            $refs.fileInput.dispatchEvent(new Event('change', { bubbles:true }));
        "
        @click="$refs.fileInput.click()"
        class="rounded-2xl border-2 border-dashed bg-gradient-to-br from-white to-neutral-50 p-6 text-center cursor-pointer transition
               hover:shadow-sm"
        :class="hover ? 'border-emerald-400 ring-2 ring-emerald-200 bg-emerald-50/30' : 'border-neutral-300'"
        role="button"
        tabindex="0"
        aria-label="Subir archivos"
    >
        <input
            type="file"
            x-ref="fileInput"
            wire:model="uploads"
            @if($multiple) multiple @endif
            accept="{{ $accept }}"
            class="hidden"
        >

        <div class="flex flex-col items-center gap-3">
            <div class="h-12 w-12 rounded-full bg-emerald-50 ring-1 ring-emerald-100 grid place-items-center">
                <svg viewBox="0 0 24 24" class="h-6 w-6 text-emerald-600">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="text-sm font-medium text-neutral-800">Arrastra archivos o haz clic para seleccionar</div>
            <div class="text-[11px] text-neutral-500">Formatos permitidos: {{ $accept }} • Máx. {{ number_format($maxSizeKB/1024,1) }} MB c/u</div>
        </div>

        <div class="text-xs text-neutral-500 mt-3" wire:loading wire:target="uploads">
            Subiendo… <span class="animate-pulse">●</span>
        </div>
        @error('uploads.*') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
    </div>

    {{-- Lista de archivos --}}
    @if(!empty($files))
        <ul class="divide-y rounded-xl border bg-white">
            @foreach($files as $i => $att)
                @php
                    $path  = $att['path'] ?? '';
                    $name  = $att['name'] ?? 'archivo';
                    $size  = isset($att['size']) ? number_format(($att['size']/1024),0).' KB' : '';
                    $mime  = $att['mime'] ?? '';
                    $isImg = preg_match('/\.(jpg|jpeg|png|webp)$/i', $path);
                @endphp

                <li class="flex items-center gap-3 p-3 hover:bg-neutral-50 transition" wire:key="dz-row-{{ $i }}">
                    {{-- Miniatura / ícono --}}
                    <div class="h-12 w-12 rounded-lg overflow-hidden bg-neutral-100 border grid place-items-center shrink-0">
                        @if($isImg)
                            <img src="{{ asset($path) }}" alt="preview {{ $name }}" class="object-cover w-full h-full">
                        @else
                            <svg viewBox="0 0 24 24" class="h-6 w-6 text-neutral-500">
                                <path d="M7 4h7l5 5v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" fill="currentColor"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Detalles --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <a href="{{ asset($path) }}" target="_blank"
                               class="text-sm font-medium text-neutral-800 hover:underline truncate">
                                {{ $name }}
                            </a>
                            @if($mime)
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-neutral-100 text-neutral-600 ring-1 ring-neutral-200">
                                    {{ strtoupper(Str::of($mime)->before('/')) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-neutral-500">{{ $size }}</p>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ asset($path) }}" target="_blank"
                           class="text-xs px-2 py-1 rounded border bg-white hover:bg-neutral-50">
                            Ver
                        </a>
                        <button type="button"
                                class="text-xs px-2 py-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200"
                                wire:click="remove({{ $i }})">
                            Quitar
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="rounded-xl border bg-neutral-50 text-neutral-600 text-xs p-3">
            Aún no hay archivos.
        </div>
    @endif
</section>
