<section
    x-data="{
        hover:false,
        // Placeholders locales que aparecen al instante
        localFiles: [],
        files: @entangle('files').live,

        addLocal(filesList){
            // Crea previews locales sin esperar al servidor
            for (const f of filesList) {
                const url = URL.createObjectURL(f);
                this.localFiles.push({
                    name: f.name, size: f.size, type: f.type, url,
                    _id: `${f.name}-${f.size}-${Date.now()}-${Math.random()}`
                });
            }
        },
        reconcile(){
            // cuando el servidor ya agregó el archivo a $files, quitamos el placeholder
            if (!Array.isArray(this.files)) return;
            this.localFiles = this.localFiles.filter(lf => {
                return !this.files.some(sf =>
                    (sf?.name ?? '') === lf.name && (parseInt(sf?.size ?? 0) === lf.size)
                );
            });
        }
    }"
    x-init="$watch(
        'files', () => reconcile()
    )"
    class="space-y-4"
>
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-neutral-800">{{ $label }}</h3>
        @if($help)
            <p class="text-xs text-neutral-500">{{ $help }}</p>
        @endif
    </div>

    {{-- Área de carga / drag & drop --}}
    <div
        @dragover.prevent="hover=true"
        @dragleave.prevent="hover=false"
        @drop.prevent="
            hover=false;
            $refs.fileInput.files = $event.dataTransfer.files;
            $refs.fileInput.dispatchEvent(new Event('change', { bubbles:true }));
        "
        @click="$refs.fileInput.click()"
        class="rounded-lg border border-neutral-300 bg-white p-5 text-center cursor-pointer transition-colors hover:bg-neutral-50"
        :class="hover ? 'ring-1 ring-neutral-300' : ''"
        role="button" tabindex="0" aria-label="Subir archivos"
    >
        <input
            type="file"
            x-ref="fileInput"
            wire:model="uploads"
            @if($multiple) multiple @endif
            accept="{{ $accept }}"
            class="hidden"
            @change="addLocal($event.target.files)"
        >

        <div class="flex flex-col items-center gap-2">
            <div class="h-9 w-9 grid place-items-center rounded-full border border-neutral-300 text-neutral-700">
                <svg viewBox="0 0 24 24" class="h-4 w-4">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <p class="text-sm text-neutral-800">Arrastra archivos o haz clic para seleccionar</p>
            <p class="text-[11px] text-neutral-500">Formatos: {{ $accept }} • Máx. {{ number_format($maxSizeKB/1024,1) }} MB</p>

            <div class="text-xs text-neutral-500 mt-1" wire:loading wire:target="uploads">
                Subiendo… <span class="animate-pulse">●</span>
            </div>
        </div>

        @error('uploads.*') <p class="text-xs text-rose-600 mt-3">{{ $message }}</p> @enderror
    </div>

    {{-- Placeholders locales (aparecen INSTANTÁNEO) --}}
    <template x-if="localFiles.length">
        <ul class="rounded-lg border border-neutral-200 divide-y bg-white">
            <template x-for="f in localFiles" :key="f._id">
                <li class="flex items-center gap-3 p-3">
                    <div class="h-10 w-10 rounded-md bg-neutral-100 border border-neutral-200 overflow-hidden grid place-items-center">
                        <template x-if="/\.(jpe?g|png|webp|gif)$/i.test(f.name)">
                            <img :src="f.url" alt="" class="object-cover w-full h-full">
                        </template>
                        <template x-if="!/\.(jpe?g|png|webp|gif)$/i.test(f.name)">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 text-neutral-500">
                                <path d="M7 4h7l5 5v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" fill="currentColor"/>
                            </svg>
                        </template>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-neutral-900 truncate" x-text="f.name"></span>
                            <span class="text-[10px] px-1 py-0.5 rounded border border-neutral-200 text-neutral-600">Cargando…</span>
                        </div>
                        <div class="mt-1 h-1.5 w-full rounded-full bg-neutral-200 overflow-hidden">
                            <div class="h-1.5 rounded-full bg-neutral-800 animate-[progress_1.2s_ease_infinite]" style="width:65%"></div>
                        </div>
                        <style>
                            @keyframes progress { 0%{transform:translateX(-60%)} 100%{transform:translateX(160%)} }
                        </style>
                        <p class="text-[11px] text-neutral-500 mt-1">Espera a que el documento cargue, por favor (99%).</p>
                    </div>

                    <div class="text-[11px] text-neutral-500 select-none">99%</div>
                </li>
            </template>
        </ul>
    </template>

    {{-- Lista real desde el servidor ($files) --}}
    @if(!empty($files))
        <ul class="rounded-lg border border-neutral-200 divide-y bg-white">
            @foreach($files as $i => $att)
                @php
                    $path  = $att['path'] ?? '';
                    $name  = $att['name'] ?? 'archivo';
                    $size  = isset($att['size']) ? number_format(($att['size']/1024),0).' KB' : '';
                    $mime  = $att['mime'] ?? '';
                    $isImg = preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $path);
                @endphp
                <li class="flex items-center gap-3 p-3" wire:key="dz-row-{{ $i }}">
                    <div class="h-10 w-10 rounded-md bg-neutral-100 border border-neutral-200 overflow-hidden grid place-items-center shrink-0">
                        @if($isImg)
                            <img src="{{ asset($path) }}" alt="preview {{ $name }}" class="object-cover w-full h-full">
                        @else
                            <svg viewBox="0 0 24 24" class="h-5 w-5 text-neutral-500">
                                <path d="M7 4h7l5 5v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" fill="currentColor"/>
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <a href="{{ asset($path) }}" target="_blank" class="text-sm text-neutral-900 hover:underline truncate">
                                {{ $name }}
                            </a>
                            @if($mime)
                                <span class="text-[10px] px-1 py-0.5 rounded border border-neutral-200 text-neutral-600">
                                    {{ strtoupper(Str::of($mime)->before('/')) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-neutral-500">{{ $size }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ asset($path) }}" target="_blank" class="text-xs text-neutral-700 hover:underline">Ver</a>
                        <button type="button" class="text-xs text-rose-600 hover:underline" wire:click="remove({{ $i }})">Quitar</button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="rounded-lg border border-neutral-200 bg-white text-neutral-600 text-xs p-3">
            Aún no hay archivos.
        </div>
    @endif
</section>
