{{-- INVITACIONES + CJA (CJA integrado; Observaciones aparte) --}}
<section x-data class="space-y-8">

  {{-- ENCABEZADO + LEYENDA --}}
  <header class="flex items-center justify-between gap-4 flex-wrap">
    <div>
      <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Invitaciones</h2>
      <p class="text-sm text-neutral-500 dark:text-neutral-400">Gestiona 1ª y 2ª invitación + CJA
        (solicitante/invitado).</p>
    </div>

    <div class="pt-2">
      <div class="flex justify-end gap-2">
        <flux:button variant="primary" wire:click="guardarInvitaciones">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
              <path d="M17 21v-8H7v8" />
              <path d="M7 3v5h8" />
            </svg>
            Grabar todo
          </span>
        </flux:button>

        <flux:modal.trigger name="cancelar">
            <flux:button variant="danger">Cancelar solicitud</flux:button>
        </flux:modal.trigger>
      </div>
    </div>
  </header>

  {{-- ============ PRIMERA + SEGUNDA INVITACIÓN + CJA ============ --}}
  <div class="rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-xl p-6 space-y-8">

    {{-- ===== Invitacion 1 y 2 ===== --}}
    @include('components.invitaciones.invitaciones')
    
    {{-- ===== CJA ===== --}}
    @include('components.invitaciones.cja-propuesta')
    
  </div> 

  {{-- ============ OBSERVACIONES (SECCIÓN APARTE) ============ --}}
  @include('components.invitaciones.observaciones')

  {{-- ========================= 
    FECHAS Y DATOS DE "MEDIACION"
    PERTENECE A LA TABLA CJA 
  ============================== --}}
  {{-- @include('components.invitaciones.fechas') --}}

  {{-- ============ MODALES ============ --}}
  @include('components.invitaciones.modales')

</section>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.store('accordion', { tab: null }); // uno abierto a la vez (global)

    Alpine.data('accordion', (idx) => ({
      idx,
      get isOpen() { return Alpine.store('accordion').tab === this.idx },
      handleClick() { Alpine.store('accordion').tab = this.isOpen ? null : this.idx },
      handleRotate() { return this.isOpen ? 'rotate-180' : '' },
      handleToggle() {
        return this.isOpen
          ? `max-height: ${this.$refs.tab.scrollHeight}px`
          : 'max-height: 0px';
      }
    }));
  });
</script>