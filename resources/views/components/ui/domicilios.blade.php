 <div class="mb-3 py-3">
        <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos domicilio</h1>
    </div>
    

    <div class=" grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Tipo domicilio
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio...">
                <flux:select.option>Casa</flux:select.option>
                <flux:select.option>Oficina</flux:select.option>
                <flux:select.option>Otro</flux:select.option>
            </flux:select>
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Calle
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:input
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
                wire:model="calle_solicitante"
                type="text"
                required
                placeholder="Calle"
            />
        </div>

        {{-- Número exterior --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Número exterior
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:input
                type="text"
                wire:model="num_ext_solicitante"
                placeholder="Ej. 123 o 123-B"
                maxlength="6"
                inputmode="text"
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[^A-Z0-9\- ]/g,'')        
                    .slice(0, 6)"                      
            />
        </div>

        {{-- Número interior --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Número interior
            </label>
            <flux:input
                type="text"
                wire:model="num_int_solicitante"
                placeholder="Ej. 2, PB, 3-A"
                maxlength="4"
                inputmode="text"
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[^A-Z0-9\-]/g,'')        
                    .slice(0, 4)"                       
            />
        </div>

    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Código Postal
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:input
        wire:model.live="cp_solicitante"
        type="text"
        required
        placeholder="Código postal"
        maxlength="5"
        inputmode="numeric"
        oninput="this.value = this.value.replace(/\D+/g,'').slice(0,5)"
      />
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Colonia
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:select wire:model="colonia" placeholder="Selecciona una colonia...">
                @foreach ($colonias as $col)
                    <flux:select.option>{{ $col->colonia }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>    
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Municipio o alcaldia
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:input
                wire:model="municipio_solicitante"
                type="text"
                readonly
                placeholder="Municipio"
            />
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Entidad Federativa
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
            <flux:input
                wire:model="entidad_federativa_solicitante"
                type="text"
                readonly
                placeholder="Entidad federativa"
            />
        </div>
    </div>