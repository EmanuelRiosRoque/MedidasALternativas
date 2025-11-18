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
        fecha: @entangle($wireModelKey ?? 'fecha').live, // YYYY-MM-DD (Para el modelo)
        mostrar: '', // DD/MM/AAAA (Para el input visible)

        abrirCalendario() {
            this.$refs.inputDate.showPicker();
        },

        // --- Lógica de Formato y Máscara (Igual que antes) ---

        aplicarMascara() {
            let v = this.mostrar.replace(/[^\d]/g, ''); // Elimina todo excepto dígitos
            
            if (v.length > 8) v = v.substring(0, 8); 

            let output = '';

            // DD
            if (v.length > 0) {
                output = v.substring(0, 2);
            }
            // DD/MM
            if (v.length > 2) {
                output += '/' + v.substring(2, 4);
            }
            // DD/MM/AAAA
            if (v.length > 4) {
                output += '/' + v.substring(4, 8);
            }
            
            this.mostrar = output; 

            if (v.length === 8) {
                this.convertirAModelo();
            } else {
                this.fecha = null; 
            }
        },

        convertirAModelo() {
            const partes = this.mostrar.split('/');
            
            if (partes.length === 3 && partes[2].length === 4) {
                const [dia, mes, anio] = partes;
                this.fecha = `${anio}-${mes}-${dia}`; 
                $dispatch('fecha-seleccionada', this.fecha);
            } else {
                this.fecha = null;
            }
        },

        formatearMostrar(dateString) {
            if (!dateString) {
                this.mostrar = '';
                return;
            }
            const [anio, mes, dia] = dateString.split('-');
            this.mostrar = `${dia}/${mes}/${anio}`;
        },

        formatearInicial() {
            const initialValue = this.fecha || @js($value);
            if (initialValue) {
                this.fecha = initialValue;
                this.formatearMostrar(initialValue);
            } else {
                this.mostrar = '';
                this.fecha = null;
            }
        }
    }"
    x-init="
        formatearInicial();
        $watch('fecha', (v) => {
            this.formatearMostrar(v);
        });
    "
    class="w-full" >
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <div class="relative flex items-stretch border border-gray-300 rounded-md shadow-sm 
        focus-within:ring-1 focus-within:ring-emerald-500 
        focus-within:border-emerald-500 transition duration-150">

        <input 
            type="text"
            x-model="mostrar"
            x-on:input="aplicarMascara()" 
            x-on:blur="convertirAModelo()" 
            x-on:keydown.enter.prevent="convertirAModelo(); $el.blur()"
            id="{{ $name }}"
            placeholder="dd/mm/aaaa"
            maxlength="10"
            class="flex-grow bg-white px-3 py-2 text-sm text-gray-700 focus:outline-none border-none rounded-l-md"
        >

        <button 
            type="button"
            @click="abrirCalendario()" 
            class="flex-shrink-0 bg-white px-3 py-2 text-sm text-gray-700 flex items-center cursor-pointer rounded-r-md border-l border-gray-300 transition duration-150 hover:bg-gray-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
            </svg>
        </button>

        <input 
            x-ref="inputDate"
            type="date"
            x-model="fecha"
            @change="formatearMostrar($el.value); convertirAModelo()"
            {{ $attributes->whereStartsWith('wire:model') }}
            tabindex="-1"
            class="absolute inset-0 opacity-0 pointer-events-none"
        >
    </div>
</div>