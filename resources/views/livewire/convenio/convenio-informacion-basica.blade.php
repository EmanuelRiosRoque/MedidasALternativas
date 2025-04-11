<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Modalidad --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Modalidad</label>
            <select wire:model.live="modalidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione una modalidad</option>
                <option value="presencial">Presencial</option>
                <option value="virtual">Virtual</option>
            </select>
        </div>

        {{-- Materia --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Materia</label>
            <select wire:model.live="materia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione una materia</option>
                <option value="civil">Civil</option>
                <option value="familiar">Familiar</option>
                <option value="mercantil">Mercantil</option>
            </select>
        </div>

        {{-- Tipo de Convenio --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo de Convenio</label>
            <select wire:model.live="tipo_convenio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione un tipo</option>
                <option value="tipo1">Tipo 1</option>
                <option value="tipo2">Tipo 2</option>
            </select>
        </div>

        {{-- Cómo se enteró --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">¿Cómo se enteró?</label>
            <input type="text" wire:model.live="como_se_entero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        {{-- Número de Ticket --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Número de Ticket</label>
            <input type="text" wire:model.live="numero_ticket" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
    </div>
</div>
