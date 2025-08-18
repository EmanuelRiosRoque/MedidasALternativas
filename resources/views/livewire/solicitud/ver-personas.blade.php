<div class="min-h-screen flex bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 relative overflow-hidden "
    x-data="{
        personas: @js($personas),
        filtro: 'todos',
        get filtradas() {
            if (this.filtro === 'todos') return this.personas;
            return this.personas.filter(persona => (persona.tipo_solicitante || '').toLowerCase() === this.filtro);
        },
    }">

    <!-- Fondo decorativo -->
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-2/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft">
    </div>

        <!-- Sidebar: Lista de personas -->
        @include('livewire.solicitud.includes.update.sidebar-lista-personas')
    
        <!-- Contenido principal -->
        @include('livewire.solicitud.includes.update.main-contenido-editable')

</div>