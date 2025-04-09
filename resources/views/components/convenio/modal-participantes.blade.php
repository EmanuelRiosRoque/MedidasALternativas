<flux:modal wire:model="mostrarModal" name="edit-profile" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                Detalles del {{ $heading }}
            </flux:heading>
            <flux:text class="mt-2">Consulta la información del registro seleccionado.</flux:text>
        </div>

        @if($detalleSeleccionado['persona'] === 'moral')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Razón social:</span> {{ $detalleSeleccionado['razon_social'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">RFC:</span> {{ $detalleSeleccionado['rfc'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Instrumento notarial:</span> {{ $detalleSeleccionado['instrumento'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Fecha del instrumento:</span> {{ $detalleSeleccionado['fecha_instrumento'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Teléfono:</span> {{ $detalleSeleccionado['telefono'] ?? '-' }}</p>
            </div>
        @endif

        @if($detalleSeleccionado['persona'] === 'fisica')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Nombre:</span> {{ $detalleSeleccionado['nombre'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Edad:</span> {{ $detalleSeleccionado['edad'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Fecha de nacimiento:</span> {{ $detalleSeleccionado['fecha_nacimiento'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Sexo:</span> {{ $detalleSeleccionado['sexo'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Escolaridad:</span> {{ $detalleSeleccionado['escolaridad'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Ocupación:</span> {{ $detalleSeleccionado['ocupacion'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Nacionalidad:</span> {{ $detalleSeleccionado['nacionalidad'] ?? '-' }}</p>
            </div>
        @endif
        
        @if ($materia === "familiar")
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Correo:</span> {{ $detalleSeleccionado['correo'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Edad:</span> {{ $detalleSeleccionado['edad'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Ocupacion:</span> {{ $detalleSeleccionado['ocupacion'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Domicilio:</span> {{ $detalleSeleccionado['domicilio'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Estado civil:</span> {{ $detalleSeleccionado['estado_civil'] ?? '-' }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Correo:</span> {{ $detalleSeleccionado['correo'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Municipio:</span> {{ $detalleSeleccionado['municipio'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Colonia:</span> {{ $detalleSeleccionado['colonia'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Calle:</span> {{ $detalleSeleccionado['calle'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100 ">Entidad federativa:</span> {{ $detalleSeleccionado['entidad_federativa'] ?? '-' }}</p>
            </div>
        @endif
        

    
        <div class="flex justify-end pt-4">
            <button 
                type="button"
                wire:click="cargarEdicion"
                class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
            >
                Editar
            </button>
        </div>
        
    </div>
</flux:modal>