@props([
    'label' => 'Selecciona archivos',
    'name' => 'archivos',
    'accept' => '.pdf,.png,.jpg,.jpeg',
    'maxSize' => 10485760, // 10MB por defecto
])

<div 
    x-data="{
        inputName: '{{ $name }}',
        files: [],
        dragging: false,
        fileData: '',

        handleFileChange(event) {
            this.handleFiles(event.target.files);
        },
        handleDrop(event) {
            this.handleFiles(event.dataTransfer.files);
            this.dragging = false;
        },
        handleFiles(fileList) {
            [...fileList].forEach(file => {
                const reader = new FileReader();
                reader.onload = () => {
                    this.files.push({
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        preview: file.type.startsWith('image/') ? reader.result : null,
                        base64: reader.result
                    });
                    this.updateFileData();
                };
                reader.readAsDataURL(file);
            });
        },
        removeFile(index) {
            this.files.splice(index, 1);
            this.updateFileData();
        },
        formatSize(bytes) {
            const kb = bytes / 1024;
            return kb > 1024 
                ? (kb / 1024).toFixed(1) + ' MB'
                : kb.toFixed(1) + ' KB';
        },
        updateFileData() {
            this.fileData = JSON.stringify(this.files.map(f => f.base64));
        }
    }"
    class="w-full max-w-xl p-1 mx-auto rounded-xl bg-white dark:bg-neutral-900 shadow"
>
    <label class="block mb-3 text-sm font-semibold text-neutral-800 dark:text-white">
        {{ $label }} <span class="text-red-500">*</span>
    </label>

    <!-- Zona de carga -->
    <div 
        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg bg-neutral-50 dark:bg-neutral-800 text-neutral-500 hover:bg-neutral-100 dark:hover:bg-neutral-700 cursor-pointer transition"
        @click="$refs.fileInput.click()"
        @dragover.prevent="dragging = true"
        @dragleave="dragging = false"
        @drop.prevent="handleDrop($event)"
        :class="{ 'border-emerald-500 bg-emerald-50 dark:bg-emerald-700/20': dragging }"
    >
        <svg class="w-8 h-8 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"/>
        </svg>
        <p>
            <span class="font-medium text-emerald-600">Arrastra aquí</span> o 
            <span class="font-medium underline">explora tus archivos</span>
        </p>
        <p class="text-xs text-neutral-400 mt-1">Máximo {{ (int) ($maxSize / 1024 / 1024) }} MB. {{ strtoupper($accept) }}</p>
        <input 
            type="file" 
            multiple 
            class="hidden" 
            x-ref="fileInput" 
            :name="inputName + '[]'"
            @change="handleFileChange"
            accept="{{ $accept }}"
        >
    </div>

    <!-- Vista previa -->
    <template x-if="files.length > 0">
        <ul class="mt-5 space-y-3">
            <template x-for="(file, index) in files" :key="index">
                <li class="flex items-center justify-between bg-neutral-100 dark:bg-neutral-700 px-4 py-2 rounded-md text-sm text-neutral-800 dark:text-white shadow-sm">
                    <div class="flex items-center space-x-3 truncate">
                        <template x-if="file.preview">
                            <img :src="file.preview" alt="" class="w-10 h-10 object-cover rounded" />
                        </template>
                        <template x-if="!file.preview">
                            <svg class="w-8 h-8 text-neutral-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z" />
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V7.828A2 2 0 0017.828 6L14 2.172A2 2 0 0012.828 2H4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <div class="truncate">
                            <p class="font-medium truncate" x-text="file.name"></p>
                            <p class="text-xs text-neutral-500" x-text="formatSize(file.size)"></p>
                        </div>
                    </div>
                    <button @click="removeFile(index)" class="text-red-500 hover:text-red-700 text-lg">✕</button>
                </li>
            </template>
        </ul>
    </template>

    <!-- Input oculto Livewire -->
    <input type="hidden" x-model="fileData" name="{{ $name }}" wire:model="{{ $name }}">
</div>
