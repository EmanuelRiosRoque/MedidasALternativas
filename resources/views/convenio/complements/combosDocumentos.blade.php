<div>
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Tipo domicilio
    </label>
    <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio del ...">
        <flux:select.option>Casa</flux:select.option>
        <flux:select.option>Oficina</flux:select.option>
        <flux:select.option>Otro</flux:select.option>
    </flux:select>
</div>