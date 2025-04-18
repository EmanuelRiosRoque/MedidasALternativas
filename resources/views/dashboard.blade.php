<x-layouts.app :title="__('Dashboard')">

    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    {{-- @if ($user==="admin")   
 --}}
        @include('dashboard.medidas')
    {{-- @else
        @include('dashboard.facilitadores')
    @endif --}}
    
</x-layouts.app>
