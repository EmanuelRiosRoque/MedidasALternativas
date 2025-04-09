<div
    x-cloak
    x-data="dropzone({
        _this: @this,
        uuid: @js($uuid),
        multiple: @js($multiple),
    })"
    @dragenter.prevent.document="onDragenter($event)"
    @dragleave.prevent="onDragleave($event)"
    @dragover.prevent="onDragover($event)"
    @drop.prevent="onDrop"
    class="relative flex flex-col items-start w-full h-full justify-center bg-transparent"
>
    @if(! is_null($error))
        <div class="bg-red-100 p-4 w-full mb-4 rounded dark:bg-red-600">
            <div class="flex gap-3 items-start">
                <svg class="w-5 h-5 text-red-500 dark:text-red-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-sm text-red-800 font-medium dark:text-red-100">{{ $error }}</h3>
            </div>
        </div>
    @endif

    <div @click="$refs.input.click()" class="group w-full">
        <div class="flex flex-col items-center justify-center py-10 px-6 bg-zinc-50 rounded-xl hover:rounded-4xl dark:bg-zinc-800 text-center relative transition-all">
            <p class="text-sm text-zinc-500 dark:text-zinc-300">
                Drop files here or <span class="font-semibold text-emerald-600 cursor-pointer">Browse</span>
            </p>

            <div x-show="isLoading"
            class="absolute top-2 right-2 w-9 h-9 rounded-full shadow-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm ring-1 ring-zinc-300 dark:ring-zinc-600 flex items-center justify-center transition-all duration-300">
            <svg class="w-7 h-7 transform -rotate-90 text-emerald-500" viewBox="0 0 36 36">
                <circle class="text-zinc-300" stroke-width="2" stroke="currentColor" fill="transparent" r="16" cx="18" cy="18" />
                <circle
                    class="text-emerald-500 transition-all duration-700 ease-out"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke="currentColor"
                    fill="transparent"
                    r="16"
                    cx="18"
                    cy="18"
                    :style="{
                        strokeDasharray: '100, 100',
                        strokeDashoffset: 100 - progress
                    }"
                />
            </svg>
            <span class="absolute text-[10px] font-semibold text-zinc-700 dark:text-zinc-300">
                <template x-if="progress < 100">
                    <span x-text="progress + '%'"></span>
                </template>
                <template x-if="progress === 100">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
            </span>
            </div>
        </div>

        
        <input
            x-ref="input"
            wire:model="upload"
            type="file"
            class="hidden"
            x-on:livewire-upload-start="isLoading = true; progress = 0"
            x-on:livewire-upload-finish="isLoading = false; progress = 100"
            x-on:livewire-upload-error="isLoading = false; progress = 0"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            @if(! is_null($this->accept)) accept="{{ $this->accept }}" @endif
            @if($multiple === true) multiple @endif
        />
    </div>

    <div class="flex justify-between w-full mt-2 text-xs text-zinc-500 dark:text-zinc-300">
        <div class="flex gap-3">
            @if($this->maxFileSize)
                <p>{{ __('Up to :size', ['size' => \Illuminate\Support\Number::fileSize($this->maxFileSize * 1024)]) }}</p>
            @endif
            @if($this->maxFileSize && !empty($this->mimes))
                <span class="text-zinc-400">·</span>
            @endif
            @if(!empty($this->mimes))
                <p>{{ Str::upper($this->mimes) }}</p>
            @endif
        </div>

        <div x-show="isLoading" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin text-zinc-300 dark:text-zinc-600 fill-zinc-800 dark:fill-zinc-200" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08154 50.5908C9.08154 73.2223 27.3685 91.5093 50 91.5093C72.6315 91.5093 90.9185 73.2223 90.9185 50.5908C90.9185 27.9593 72.6315 9.67236 50 9.67236C27.3685 9.67236 9.08154 27.9593 9.08154 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7232 75.2124 7.4129C69.5422 4.1026 63.2754 1.94025 56.7698 1.05124C51.7666 0.367168 46.6976 0.446843 41.7345 1.27873C39.2615 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0597 10.1071C47.8511 9.53852 51.7191 9.52689 55.5402 10.0723C60.8643 10.803 65.9928 12.6033 70.6331 15.3616C75.2735 18.1199 79.3347 21.775 82.5849 26.1028C84.9175 29.2475 86.7993 32.723 88.1811 36.4118C89.083 38.7672 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>              
            <span class="text-xs underline cursor-pointer" @click="cancelUpload">Cancel upload</span>
        </div>
    </div>

    @if(isset($files) && count($files) > 0)
    <div class="flex flex-wrap gap-4 justify-start w-full mt-2" x-data>
        @foreach($files as $file)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="flex items-center justify-between gap-2 w-full p-3 rounded-xl border border-emerald-200/20 shadow-md shadow-emerald-900/5 dark:border-zinc-700 transition-all duration-300 hover:scale-[1.01] hover:shadow-xl hover:shadow-emerald-800/10"
        >
            <div class="flex items-center gap-4">
                @if($this->isImageMime($file['extension']))
                    <div class="w-14 h-14 overflow-hidden rounded-lg ring-1 ring-zinc-300/10 dark:ring-zinc-700">
                        <img src="{{ $file['temporaryUrl'] }}" class="w-full h-full object-cover" />
                    </div>
                @elseif(strtolower($file['extension']) === 'pdf')
                    <div class="w-14 h-14 flex items-center justify-center bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300 rounded-lg shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0a2 2 0 012 2v4h4a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V2a2 2 0 012-2h6zm2 2h-6v4h6V2z" />
                        </svg>
                    </div>
                @else
                    <div class="w-14 h-14 flex items-center justify-center bg-zinc-100 dark:bg-zinc-700 rounded-lg">
                        <svg class="w-6 h-6 text-zinc-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 2a2 2 0 00-2 2v16c0 1.1.9 2 2 2h12a2 2 0 002-2V8l-6-6H6zm7 1.5L18.5 9H13V3.5z" />
                          </svg>
                          
                    </div>
                @endif
    
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate max-w-[200px]">{{ $file['name'] }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ \Illuminate\Support\Number::fileSize($file['size']) }}</span>
                </div>
            </div>
    
            <button type="button" 
                @click.stop="show = false; $nextTick(() => removeUpload('{{ $file['tmpFilename'] }}'))"
                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 transition-all duration-200 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-800/30">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endforeach
    </div>
    
    
    @endif

    @script
    <script>
        Alpine.data('dropzone', ({ _this, uuid, multiple }) => {
            return {
                isDragging: false,
                isDropped: false,
                isLoading: false,
                progress: 0,

                onDrop(e) {
                    this.isDropped = true;
                    this.isDragging = false;

                    const file = multiple ? e.dataTransfer.files : e.dataTransfer.files[0];

                    const args = ['upload', file, () => {
                        this.isLoading = false;
                        this.progress = 100;
                    }, (error) => {
                        console.log('livewire-dropzone upload error', error);
                        this.isLoading = false;
                        this.progress = 0;
                    }, () => {
                        this.isLoading = true;
                        this.progress = 0;
                    }];

                    multiple ? _this.uploadMultiple(...args) : _this.upload(...args);
                },

                onDragenter() {
                    this.isDragging = true;
                },
                onDragleave() {
                    this.isDragging = false;
                },
                onDragover() {
                    this.isDragging = true;
                },

                cancelUpload() {
                    _this.cancelUpload('upload');
                    this.isLoading = false;
                    this.progress = 0;
                },

                removeUpload(tmpFilename) {
                    _this.dispatch(uuid + ':fileRemoved', { tmpFilename });
                },
            };
        });
    </script>
    @endscript
</div>
