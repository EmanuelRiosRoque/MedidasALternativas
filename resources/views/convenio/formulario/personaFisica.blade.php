@props(['prefix'])

<div class="gap-4 mt-2" wire:key="{{ $key }}"
x-data="{
    fecha: @entangle('fecha_nacimiento_solicitante').live,
    setEdad(v) { $wire.set('edad_solicitante', v?.toString() ?? ''); },
    calcEdad(iso) {
      if (!iso) return '';
      const d = new Date(iso + 'T00:00:00'); // evita desfases TZ
      const hoy = new Date();
      let edad = hoy.getFullYear() - d.getFullYear();
      const m = hoy.getMonth() - d.getMonth();
      if (m < 0 || (m === 0 && hoy.getDate() < d.getDate())) edad--;
      return (edad >= 0 && edad <= 130) ? edad : '';
    }
  }"
  x-init="$watch('fecha', v => setEdad(calcEdad(v)))"
>

  {{-- ================= DATOS PERSONALES ================= --}}
  <div class="grid grid-cols-3 gap-4">

    {{-- Nombre --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Nombre *</label>
      <flux:input
        wire:model="nombre_solicitante"
        type="text"
        required
        placeholder="Nombre"
        oninput="this.value = this.value
          .toUpperCase()
          .replace(/[ÁÀÂÄ]/g,'A')
          .replace(/[ÉÈÊË]/g,'E')
          .replace(/[ÍÌÎÏ]/g,'I')
          .replace(/[ÓÒÔÖ]/g,'O')
          .replace(/[ÚÙÛÜ]/g,'U')"
      />
    </div>

    {{-- Apellido paterno --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Apellido paterno *</label>
      <flux:input
        wire:model="apellido_p_solicitante"
        type="text"
        required
        placeholder="Apellido paterno"
        oninput="this.value = this.value
          .toUpperCase()
          .replace(/[ÁÀÂÄ]/g,'A')
          .replace(/[ÉÈÊË]/g,'E')
          .replace(/[ÍÌÎÏ]/g,'I')
          .replace(/[ÓÒÔÖ]/g,'O')
          .replace(/[ÚÙÛÜ]/g,'U')"
      />
    </div>

    {{-- Apellido materno --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Apellido materno *</label>
      <flux:input
        wire:model="apellido_m_solicitante"
        type="text"
        required
        placeholder="Apellido materno"
        oninput="this.value = this.value
          .toUpperCase()
          .replace(/[ÁÀÂÄ]/g,'A')
          .replace(/[ÉÈÊË]/g,'E')
          .replace(/[ÍÌÎÏ]/g,'I')
          .replace(/[ÓÒÔÖ]/g,'O')
          .replace(/[ÚÙÛÜ]/g,'U')"
      />
    </div>

    {{-- RFC --}}
    <div class="space-y-2">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">RFC
        @if ($prefix === 'solicitante')
          *
        @endif
      </label>
      <flux:input
        wire:model="rfc_solicitante"
        type="text"
        required
        placeholder="RFC"
        oninput="this.value = this.value
          .toUpperCase()
          .replace(/[ÁÀÂÄ]/g,'A')
          .replace(/[ÉÈÊË]/g,'E')
          .replace(/[ÍÌÎÏ]/g,'I')
          .replace(/[ÓÒÔÖ]/g,'O')
          .replace(/[ÚÙÛÜ]/g,'U')"
      />
    </div>

    {{-- Sexo --}}
    <div>
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Sexo @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo...">
        <flux:select.option value="1">Femenino</flux:select.option>
        <flux:select.option value="2">Masculino</flux:select.option>
      </flux:select>
    </div>


    {{-- Fecha de nacimiento (sin oninput innecesario) --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Fecha de nacimiento @if($prefix === 'solicitante') * @endif
      </label>
      <flux:input
        wire:model="fecha_nacimiento_solicitante"
        type="date"
        required
      />
    </div>

    {{-- Edad (solo dígitos) --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Edad @if($prefix === 'solicitante') * @endif
      </label>
      <flux:input
        wire:model="edad_solicitante"
        type="text"
        required
        placeholder="Edad"
        inputmode="numeric"
        maxlength="3"
        oninput="this.value = this.value.replace(/\D+/g,'').slice(0,3)"
      />
    </div>

   

    {{-- Escolaridad --}}
    <div>
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Escolaridad @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="escolaridad_solicitante" placeholder="Elige escolaridad...">
        @foreach ($escolaridades as $escolaridad)
          <flux:select.option value="{{ $escolaridad->id }}">{{ $escolaridad->nombre }}</flux:select.option>
        @endforeach
      </flux:select>
    </div>

    {{-- Ocupación --}}
    <div>
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Ocupación @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="ocupacion_solicitante" placeholder="Elige Ocupació...">
        @foreach ($ocupaciones as $ocupacion)
          <flux:select.option value="{{ $ocupacion->id }}">{{ $ocupacion->nombre }}</flux:select.option>
        @endforeach
      </flux:select>
    </div>

    {{-- Nacionalidad --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Nacionalidad @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="nacionalidad_solicitante" placeholder="Elige tipo nacionalidad...">
        <flux:select.option value="1">Mexicana</flux:select.option>
        <flux:select.option value="2">Extranjera</flux:select.option>
      </flux:select>
    </div>
  </div>

  {{-- ================= DATOS DE CONTACTO ================= --}}
  <div class="mb-3 py-3">
    <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos de Contacto</h1>
  </div>

  <flux:modal.trigger name="edit-contactos">
    <flux:button>Datos de Contacto</flux:button>
  </flux:modal.trigger>

  @if ($errors->has('correos') || $errors->has('telefonos'))
    <p class="text-red-500 text-sm mt-1">Hace falta agregar al menos un correo o teléfono.</p>
  @endif

  <flux:modal name="edit-contactos" class="md:w-96">
    <div class="space-y-6">
      <div>
        <flux:heading size="lg">Datos de contacto</flux:heading>
      </div>

      {{-- Correos electrónicos --}}
      <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
          Correos electrónicos
          @if (!($modalidad == 1 && $prefix === 'invitado')) * @endif
        </label>

        <div class="flex gap-2">
          <flux:input
            wire:model.defer="correo_temp"
            type="email"
            placeholder="Agregar correo"
            oninput="this.value = this.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
          />
          <x-boton-agregar wire-click="agregarCorreo" />
        </div>

        @if (!empty($correos))
          <ul class="mt-2 space-y-1">
            @foreach ($correos as $i => $correo)
              <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                <span class="truncate">{{ $correo }}</span>
                <button
                  wire:click="eliminarCorreo({{ $i }})"
                  class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                  title="Eliminar"
                >×</button>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      {{-- Teléfonos (solo dígitos) --}}
      <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
          Teléfonos
          @if (!($modalidad == 2 && $prefix === 'invitado')) * @endif
        </label>

        <div class="flex gap-2">
          <flux:input
            wire:model.defer="telefono_temp"
            type="tel"
            placeholder="Agregar teléfono"
            inputmode="tel"
            maxlength="10"
            oninput="this.value = this.value.replace(/\D+/g,'').slice(0,10)"
          />
          <x-boton-agregar wire-click="agregarTelefono" />
        </div>

        @if (!empty($telefonos))
          <ul class="mt-2 space-y-1">
            @foreach ($telefonos as $i => $tel)
              <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                <span class="truncate">{{ $tel }}</span>
                <button
                  wire:click="eliminarTelefono({{ $i }})"
                  class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                  title="Eliminar"
                >×</button>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="flex">
        <flux:spacer />
        <flux:modal.close>
          <flux:button variant="ghost">Cerrar</flux:button>
        </flux:modal.close>
      </div>
    </div>
  </flux:modal>

  {{-- ================= DATOS DOMICILIO ================= --}}
  <div class="mb-3 py-3">
    <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos domicilio</h1>
  </div>

  <div class="grid grid-cols-3 gap-4">

    {{-- Tipo domicilio --}}
    <div>
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Tipo domicilio @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio...">
        <flux:select.option>Casa</flux:select.option>
        <flux:select.option>Oficina</flux:select.option>
        <flux:select.option>Otro</flux:select.option>
      </flux:select>
    </div>

    {{-- Calle --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Calle @if($prefix === 'solicitante') * @endif
      </label>
      <flux:input
        wire:model="calle_solicitante"
        type="text"
        required
        placeholder="Calle"
        oninput="this.value = this.value
          .toUpperCase()
          .replace(/[ÁÀÂÄ]/g,'A')
          .replace(/[ÉÈÊË]/g,'E')
          .replace(/[ÍÌÎÏ]/g,'I')
          .replace(/[ÓÒÔÖ]/g,'O')
          .replace(/[ÚÙÛÜ]/g,'U')"
      />
    </div>

    {{-- Código Postal (solo dígitos; se mantiene .live) --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Código Postal @if($prefix === 'solicitante') * @endif
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

    {{-- Colonia --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Colonia @if($prefix === 'solicitante') * @endif
      </label>
      <flux:select wire:model="colonia" placeholder="Selecciona una colonia...">
        @foreach ($colonias as $col)
          <flux:select.option>{{ $col->colonia }}</flux:select.option>
        @endforeach
      </flux:select>
    </div>

    {{-- Municipio --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Municipio o alcaldia @if($prefix === 'solicitante') * @endif
      </label>
      <flux:input
        wire:model="municipio_solicitante"
        type="text"
        readonly
        placeholder="Municipio"
      />
    </div>

    {{-- Entidad Federativa --}}
    <div class="space-y-1">
      <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Entidad Federativa @if($prefix === 'solicitante') * @endif
      </label>
      <flux:input
        wire:model="entidad_federativa_solicitante"
        type="text"
        readonly
        placeholder="Entidad federativa"
      />
    </div>

  </div>
</div>
