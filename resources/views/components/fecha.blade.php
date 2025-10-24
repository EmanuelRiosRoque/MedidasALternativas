@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
  function initFechasEs(scope=document){
    scope.querySelectorAll('input.fecha-es').forEach(el => {
      if (el._flatpickr) return;
      flatpickr(el, {
        altInput: true,             // lo que ve el usuario
        altFormat: 'd/m/Y',         // dd/mm/aaaa
        dateFormat: 'Y-m-d',        // lo que guarda Livewire
        locale: flatpickr.l10ns.es,
        allowInput: true
      });
    });
  }
  document.addEventListener('DOMContentLoaded', () => initFechasEs());
  document.addEventListener('livewire:init', () => {
    Livewire.hook('message.processed', (_m, comp) => initFechasEs(comp.el));
  });
</script>
@endpush
