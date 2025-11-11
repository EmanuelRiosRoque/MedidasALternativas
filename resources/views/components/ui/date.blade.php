@props([
    'label' => '',
    'name' => 'fecha',
    'value' => null,
])

@php
    use Illuminate\Support\Str;

    // Detecta si el componente tiene un wire:model asociado
    $wireModelAttr = $attributes->whereStartsWith('wire:model')->first();
    $wireModelKey = null;

    if ($wireModelAttr) {
        $wireModelKey = Str::after($wireModelAttr, 'wire:model');
        $wireModelKey = ltrim(str_replace(['=', '"', "'"], '', $wireModelKey)); // Limpia el nombre
    }
@endphp

<div 
    x-data="{
        fecha: @entangle($wireModelKey ?? 'fecha').live,
        mostrar: '',

        abrirCalendario() {
            this.$refs.inputDate.showPicker();
        },

        actualizar(e) {
            if (!e.target.value) return;
            const [anio, mes, dia] = e.target.value.split('-');
            this.fecha = e.target.value;
            this.mostrar = `${dia}/${mes}/${anio}`;
            $dispatch('fecha-seleccionada', this.fecha); // evento opcional
        },

        formatearInicial() {
            if (this.fecha) {
                const [anio, mes, dia] = this.fecha.split('-');
                this.mostrar = `${dia}/${mes}/${anio}`;
            } else if (@js($value)) {
                const [anio, mes, dia] = @js($value).split('-');
                this.mostrar = `${dia}/${mes}/${anio}`;
                this.fecha = @js($value);
            } else {
                this.mostrar = '';
            }
        }
    }"
    x-init="
        formatearInicial();

        $watch('fecha', (v) => {
            if (!v) { this.mostrar = ''; return; }
            const [anio, mes, dia] = v.split('-');
            this.mostrar = `${dia}/${mes}/${anio}`;
        });
    "
    class="w-full"
>
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <div 
        @click="abrirCalendario()" 
        class="relative border rounded-md bg-white px-3 py-2 text-sm text-gray-700 flex items-center justify-between shadow-sm cursor-pointer focus:ring-2 focus:ring-emerald-500"
    >
        <span x-text="mostrar || 'dd/mm/aaaa'" class="text-gray-500"></span>

        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
        </svg>

        <!-- Input oculto -->
        <input 
            x-ref="inputDate"
            type="date"
            name="{{ $name }}"
            x-model="fecha"
            @change="actualizar($event)"
            {{ $attributes->whereStartsWith('wire:model') }}
            class="absolute inset-0 opacity-0 cursor-pointer"
        >
    </div>
</div>
