<div class="space-y-1 flex flex-col items-center mt-4">
  <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
    Convenio(s) / Acuerdos (PDF)
  </label>

  <div class="w-full max-w-sm">
    <livewire:dropzone
        wire:model="acuerdos"
        :rules="['mimes:pdf','max:10420']"
        :multiple="true"
        wire:key="dropzone-convenio"
    />
  </div>

  @error('acuerdos') 
    <p class="text-xs text-red-600">{{ $message }}</p>
  @enderror
</div>
