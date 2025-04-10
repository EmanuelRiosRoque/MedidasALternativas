<x-layouts.app :title="__('Dashboard')">
    {{-- @php
        $user="lector"    
    @endphp

    @if ($user==="admin")   --}}
        @include('dashboard.medidas')
    {{-- @else
        @include('dashboard.facilitadores')
    @endif --}}
    
</x-layouts.app>
