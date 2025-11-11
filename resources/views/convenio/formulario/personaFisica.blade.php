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
   
<x-ui.date
    label="Fecha nacimiento"
    name="fecha_nacimiento_solicitante"
    wire:model="fecha_nacimiento_solicitante"
/>



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
  @include('components.ui.datos-contacto')

  {{-- ================= DATOS DOMICILIO ================= --}}
  @include('components.ui.domicilios')
  
</div>
