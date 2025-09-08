<flux:modal wire:model="mostrarModal" name="edit-profile" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                Detalles del {{ $heading }}
            </flux:heading>
            <flux:text class="mt-2">Consulta la información del registro seleccionado.</flux:text>
        </div>

     
      
        @if ($detalleSeleccionado['persona'] === 'moral')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">

                @if (!empty($detalleSeleccionado['razon_social']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Razón social:</span> {{ $detalleSeleccionado['razon_social'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['rfc']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">RFC:</span> {{ $detalleSeleccionado['rfc'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['instrumento']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Instrumento notarial:</span> {{ $detalleSeleccionado['instrumento'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['fecha_instrumento']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Fecha del instrumento:</span> {{ $detalleSeleccionado['fecha_instrumento'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['como_se_entero']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">¿Cómo se enteró?:</span> {{ $detalleSeleccionado['como_se_entero'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['correos']) && is_array($detalleSeleccionado['correos']))
                    <div class="col-span-2">
                        <p class="font-semibold text-neutral-800 dark:text-neutral-100">Correos:</p>
                        <ul class="list-none ml-2 space-y-1">
                            @foreach ($detalleSeleccionado['correos'] as $correo)
                                <li>- {{ $correo }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!empty($detalleSeleccionado['telefonos']) && is_array($detalleSeleccionado['telefonos']))
                    <div class="col-span-2">
                        <p class="font-semibold text-neutral-800 dark:text-neutral-100">Teléfonos:</p>
                        <ul class="list-none ml-2 space-y-1">
                            @foreach ($detalleSeleccionado['telefonos'] as $tel)
                                <li>- {{ $tel }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-span-2 space-y-1 mt-2">
                    <p class="font-semibold text-neutral-800 dark:text-neutral-100">Domicilio:</p>
                    <ul class="list-none ml-2 text-sm text-neutral-600 dark:text-neutral-300 space-y-1">
                        <li><span class="font-medium">Tipo:</span> {{ $detalleSeleccionado['tipo_domicilio'] ?? '-' }}</li>
                        <li><span class="font-medium">Calle:</span> {{ $detalleSeleccionado['calle'] ?? '-' }}</li>
                        <li><span class="font-medium">Colonia:</span> {{ $detalleSeleccionado['colonia'] ?? '-' }}</li>
                        <li><span class="font-medium">Código Postal:</span> {{ $detalleSeleccionado['cp'] ?? '-' }}</li>
                        <li><span class="font-medium">Municipio:</span> {{ $detalleSeleccionado['municipio'] ?? '-' }}</li>
                        <li><span class="font-medium">Entidad Federativa:</span> {{ $detalleSeleccionado['entidad_federativa'] ?? '-' }}</li>
                    </ul>
                </div>

            
            </div>
        @endif

        @if ($detalleSeleccionado['persona'] === 'fisica')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">

                @if (!empty($detalleSeleccionado['como_se_entero']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">¿Cómo se enteró?:</span> {{ $detalleSeleccionado['como_se_entero'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['nombre']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Nombre:</span> {{ $detalleSeleccionado['nombre'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['sexo']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Sexo:</span> {{ $detalleSeleccionado['sexo'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['edad']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Edad:</span> {{ $detalleSeleccionado['edad'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['fecha_nacimiento']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Fecha de nacimiento:</span> {{ $detalleSeleccionado['fecha_nacimiento'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['escolaridad']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Escolaridad:</span> {{ $detalleSeleccionado['escolaridad'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['ocupacion']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Ocupación:</span> {{ $detalleSeleccionado['ocupacion'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['nacionalidad']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Nacionalidad:</span> {{ $detalleSeleccionado['nacionalidad'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['rfc']))
                    <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">RFC:</span> {{ $detalleSeleccionado['rfc'] }}</p>
                @endif

                @if (!empty($detalleSeleccionado['correos']) && is_array($detalleSeleccionado['correos']))
                    <div class="col-span-2">
                        <p class="font-semibold text-neutral-800 dark:text-neutral-100">Correos:</p>
                        <ul class="list-none ml-2 text-sm space-y-1">
                            @foreach ($detalleSeleccionado['correos'] as $correo)
                                <li>- {{ $correo }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!empty($detalleSeleccionado['telefonos']) && is_array($detalleSeleccionado['telefonos']))
                    <div class="col-span-2">
                        <p class="font-semibold text-neutral-800 dark:text-neutral-100">Teléfonos:</p>
                        <ul class="list-none ml-2 text-sm space-y-1">
                            @foreach ($detalleSeleccionado['telefonos'] as $tel)
                                <li>- {{ $tel }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-span-2 space-y-1">
                    <p class="font-semibold text-neutral-800 dark:text-neutral-100">Domicilio:</p>
                    <ul class="list-none ml-2 text-sm text-neutral-600 dark:text-neutral-300 space-y-1">
                        <li><span class="font-medium">Tipo:</span> {{ $detalleSeleccionado['tipo_domicilio'] ?? '-' }}</li>
                        <li><span class="font-medium">Calle:</span> {{ $detalleSeleccionado['calle'] ?? '-' }}</li>
                        <li><span class="font-medium">Colonia:</span> {{ $detalleSeleccionado['colonia'] ?? '-' }}</li>
                        <li><span class="font-medium">Código Postal:</span> {{ $detalleSeleccionado['cp'] ?? '-' }}</li>
                        <li><span class="font-medium">Municipio:</span> {{ $detalleSeleccionado['municipio'] ?? '-' }}</li>
                        <li><span class="font-medium">Entidad Federativa:</span> {{ $detalleSeleccionado['entidad_federativa'] ?? '-' }}</li>
                    </ul>
                </div>


                {{-- Documentos --}}
                {{-- @foreach (['identificacion' => 'Identificación', 'formato_privacidad' => 'Formato de privacidad'] as $campo => $label)
                    @if (!empty($detalleSeleccionado[$campo]) && is_array($detalleSeleccionado[$campo]))
                        <div class="col-span-2">
                            <p class="font-semibold text-neutral-800 dark:text-neutral-100 mb-1">{{ $label }}:</p>
                            <a href="{{ $detalleSeleccionado[$campo][0] }}" target="_blank" class="text-emerald-600 hover:underline text-sm">
                                Ver documento
                            </a>
                        </div>
                    @endif
                @endforeach --}}
            </div>
        @endif

      

        
        @if ($materia === "familiar")
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-neutral-500 dark:text-neutral-300">
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Nombre:</span> {{ $detalleSeleccionado['nombre'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Sexo:</span> {{ $detalleSeleccionado['sexo'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Edad:</span> {{ $detalleSeleccionado['edad'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Escolaridad:</span> {{ $detalleSeleccionado['escolaridad'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Ocupación:</span> {{ $detalleSeleccionado['ocupacion'] ?? '-' }}</p>
                <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Estado civil:</span> {{ $detalleSeleccionado['estado_civil'] ?? '-' }}</p>

                <div class="col-span-2">
                    <p class="font-semibold text-neutral-800 dark:text-neutral-100">Correos:</p>
                    @if (!empty($detalleSeleccionado['correos']) && is_array($detalleSeleccionado['correos']))
                        <ul class="list-none ml-2 space-y-1 text-sm text-neutral-600 dark:text-neutral-300">
                            @foreach ($detalleSeleccionado['correos'] as $correo)
                                <li>- {{ $correo }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="ml-2">-</p>
                    @endif
                </div>

                <div class="col-span-2">
                    <p class="font-semibold text-neutral-800 dark:text-neutral-100">Teléfonos:</p>
                    @if (!empty($detalleSeleccionado['telefonos']) && is_array($detalleSeleccionado['telefonos']))
                        <ul class="list-none ml-2 space-y-1 text-sm text-neutral-600 dark:text-neutral-300">
                            @foreach ($detalleSeleccionado['telefonos'] as $tel)
                                <li>- {{ $tel }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="ml-2">-</p>
                    @endif
                </div>

                <div class="col-span-2 space-y-1 mt-2">
                    <p class="font-semibold text-neutral-800 dark:text-neutral-100">Domicilio:</p>
                    <ul class="list-none ml-2 text-sm text-neutral-600 dark:text-neutral-300 space-y-1">
                        <li><span class="font-medium">Tipo:</span> {{ $detalleSeleccionado['tipo_domicilio'] ?? '-' }}</li>
                        <li><span class="font-medium">Calle:</span> {{ $detalleSeleccionado['calle'] ?? '-' }}</li>
                        <li><span class="font-medium">Colonia:</span> {{ $detalleSeleccionado['colonia'] ?? '-' }}</li>
                        <li><span class="font-medium">Código Postal:</span> {{ $detalleSeleccionado['cp'] ?? '-' }}</li>
                        <li><span class="font-medium">Municipio:</span> {{ $detalleSeleccionado['municipio'] ?? '-' }}</li>
                        <li><span class="font-medium">Entidad Federativa:</span> {{ $detalleSeleccionado['entidad_federativa'] ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        @endif

          @if ($detalleSeleccionado['representante'] != null)
          <div class="text-sm text-neutral-500 dark:text-neutral-300">
              <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Nombre representante:</span> {{ $detalleSeleccionado['nombre_representante'] }}</p>
              <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Apellido paterno:</span> {{ $detalleSeleccionado['apellido_p_representante'] }}</p>
              <p><span class="font-semibold text-neutral-800 dark:text-neutral-100">Apellido materno:</span> {{ $detalleSeleccionado['apellido_m_representante'] }}</p>
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