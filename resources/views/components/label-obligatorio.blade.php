@props([
    'texto' => '',
    'mostrar' => true
])

<div class="flex items-center justify-between">
    <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
        {{ $texto }}
    </label>

    @if($mostrar)
        <flux:badge color="emerald" size="sm" title="Campo obligatorio">
            <x-lucide-asterisk class="w-4 h-4" />
        </flux:badge>
    @endif
</div>
