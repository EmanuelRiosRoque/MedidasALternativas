<div
    x-cloak
    x-data="dropzone({
        _this: @this,
        uuid: @js($uuid),
        multiple: @js($multiple),
        field: 'upload', // <-- propiedad del componente Livewire (por instancia)
    })"
    @dragenter.prevent.document="onDragenter($event)"
    @dragleave.prevent="onDragleave($event)"
    @dragover.prevent="onDragover($event)"
    @drop.prevent="onDrop"
    class="w-full"
>
    {{-- Error minimal + dark --}}
    @if(! is_null($error))
        <div class="mb-3 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-900/20 dark:text-red-200">
            {{ $error }}
        </div>
    @endif

    {{-- Área drop/Browse minimal + dark --}}
    <div @click="$refs.input.click()" class="w-full">
        <div
            class="flex flex-col items-center justify-center rounded-md border border-dashed border-zinc-300 bg-white px-4 py-10 text-center hover:border-zinc-400 dark:border-zinc-700 dark:bg-zinc-800"
            :class="isDragging ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : ''"
        >
            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                Arrastra archivos aquí o
                <span class="cursor-pointer font-medium text-emerald-600 underline underline-offset-2">Buscar</span>
            </p>

            {{-- Progreso: determinate (0-100) y luego indeterminate "Procesando…" --}}
            <template x-if="isLoading">
                <div class="mt-4 w-full max-w-xs">
                    <template x-if="!isProcessing">
                        <div>
                            <div class="h-1 w-full rounded bg-zinc-200 dark:bg-zinc-700">
                                <div class="h-1 rounded bg-emerald-500 dark:bg-emerald-400 transition-all" :style="`width:${progress}%;`"></div>
                            </div>
                            <div class="mt-1 text-right text-[11px] text-zinc-500 dark:text-zinc-400" x-text="'Subiendo… ' + progress + '%'"></div>
                        </div>
                    </template>

                    <template x-if="isProcessing">
                        <div>
                            <div class="h-1 w-full rounded progress-indeterminate"></div>
                            <div class="mt-1 text-right text-[11px] text-zinc-500 dark:text-zinc-400" x-text="progressLabel"></div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <input
            x-ref="input"
            wire:model="upload"  {{-- <-- propiedad real del componente Livewire --}}
            type="file"
            class="hidden"
            x-on:livewire-upload-start="
                isLoading = true;
                isProcessing = false;
                progress = 0;
                progressLabel = 'Subiendo… 0%';
            "
            x-on:livewire-upload-progress="
                progress = $event.detail.progress;   // 0–100 exacto
                progressLabel = 'Subiendo… ' + progress + '%';
            "
            x-on:livewire-upload-finish="
                startProcessing();
                if (!processingFinishTimer) {
                    processingFinishTimer = setTimeout(() => stopProcessing(), 1200);
                }
            "
            x-on:livewire-upload-error="
                stopProcessing(true);
            "
            @if(! is_null($this->accept)) accept="{{ $this->accept }}" @endif
            @if($multiple === true) multiple @endif
        />
    </div>

    {{-- Metadatos --}}
    <div class="mt-2 flex w-full items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
        <div class="flex gap-2">
            @if($this->maxFileSize)
                <span>{{ __('Hasta :size', ['size' => \Illuminate\Support\Number::fileSize($this->maxFileSize * 1024)]) }}</span>
            @endif
            @if($this->maxFileSize && !empty($this->mimes))
                <span>·</span>
            @endif
            @if(!empty($this->mimes))
                <span>{{ Str::upper($this->mimes) }}</span>
            @endif
        </div>

        <button
            type="button"
            x-show="isLoading"
            @click="cancelUpload"
            class="text-xs text-zinc-600 hover:text-zinc-800 underline underline-offset-2 dark:text-zinc-300 dark:hover:text-white"
        >
            Cancelar
        </button>
    </div>

    {{-- Lista de archivos (tu misma UI) --}}
    @if(isset($files) && count($files) > 0)
        <div class="mt-3 space-y-2">
            @foreach($files as $file)
                <div x-data="{ show: true }" x-show="show"
                     class="flex items-center justify-between rounded border border-zinc-200 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex min-w-0 items-center gap-3">
                        @if($this->isImageMime($file['extension']))
                            <div class="h-8 w-8 overflow-hidden rounded bg-zinc-100 dark:bg-zinc-700">
                                <img src="{{ $file['temporaryUrl'] }}" class="h-full w-full object-cover" />
                            </div>
                        @elseif(strtolower($file['extension']) === 'pdf')
                            <div class="flex h-8 w-8 items-center justify-center rounded bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">PDF</div>
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">
                                {{ Str::upper($file['extension']) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <div class="truncate text-sm text-zinc-800 dark:text-zinc-100">{{ $file['name'] }}</div>
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ \Illuminate\Support\Number::fileSize($file['size']) }}</div>
                        </div>
                    </div>

                    <button type="button"
                        @click.stop="show = false; $nextTick(() => removeUpload('{{ $file['tmpFilename'] }}'))"
                        class="text-xs text-red-600 hover:text-red-700 underline underline-offset-2 dark:text-red-400 dark:hover:text-red-300">
                        Quitar
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    @once
        <style>
            @keyframes dz-indeterminate {
                0%   { left: -40%; width: 40%; }
                50%  { left: 20%;  width: 60%; }
                100% { left: 100%; width: 40%; }
            }
            .progress-indeterminate {
                position: relative;
                overflow: hidden;
                background-color: #e4e4e7;
                border-radius: 0.25rem;
                height: 0.25rem;
            }
            .progress-indeterminate::before {
                content: "";
                position: absolute;
                inset: 0 auto 0 0;
                height: 100%;
                background-color: #10b981;
                animation: dz-indeterminate 1.1s ease-in-out infinite;
                border-radius: 0.25rem;
            }
            .dark .progress-indeterminate { background-color: #3f3f46; }
            .dark .progress-indeterminate::before { background-color: #34d399; }
        </style>
    @endonce

    @script
    <script>
        Alpine.data('dropzone', ({ _this, uuid, multiple, field }) => ({
            isDragging: false,
            isDropped: false,
            isLoading: false,
            isProcessing: false,
            progress: 0,
            progressLabel: '',
            processingFinishTimer: null,
            field, // siempre 'upload' en este componente

            init() {
                // Si el servidor emite: $this->dispatchBrowserEvent('dropzone:processed');
                window.addEventListener('dropzone:processed', () => this.stopProcessing());
            },

            onDrop(e) {
                this.isDropped = true;
                this.isDragging = false;

                const finish = () => {
                    this.startProcessing();
                    if (!this.processingFinishTimer) {
                        this.processingFinishTimer = setTimeout(() => this.stopProcessing(), 1200);
                    }
                };
                const error = () => this.stopProcessing(true);
                const progress = (evt) => {
                    this.isLoading = true;
                    this.isProcessing = false;
                    this.progress = evt.detail.progress; // 0–100 exacto
                    this.progressLabel = 'Subiendo… ' + this.progress + '%';
                };

                if (multiple) {
                    _this.uploadMultiple(this.field, e.dataTransfer.files, finish, error, progress);
                } else {
                    _this.upload(this.field, e.dataTransfer.files[0], finish, error, progress);
                }
            },

            onDragenter() { this.isDragging = true;  },
            onDragleave() { this.isDragging = false; },
            onDragover()  { this.isDragging = true;  },

            startProcessing() {
                this.isProcessing  = true;
                this.isLoading     = true;
                this.progress      = 100;
                this.progressLabel = 'Procesando…';
            },

            stopProcessing(isError = false) {
                if (this.processingFinishTimer) {
                    clearTimeout(this.processingFinishTimer);
                    this.processingFinishTimer = null;
                }
                this.isProcessing = false;
                this.isLoading    = false;
                if (isError) { this.progress = 0; this.progressLabel = ''; }
            },

            cancelUpload() {
                _this.cancelUpload(this.field);
                this.stopProcessing(true);
            },

            removeUpload(tmpFilename) {
                _this.dispatch(uuid + ':fileRemoved', { tmpFilename });
            },
        }));
    </script>
    @endscript
</div>
