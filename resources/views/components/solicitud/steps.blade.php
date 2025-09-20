@props([
  'steps' => [],
  'current' => 1,
])
<ol class="flex items-center gap-3 text-sm">
  @foreach($steps as $id => $label)
    @php $done = $current > $id; $active = $current === $id; @endphp
    <li class="flex items-center gap-2">
      <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $done ? 'bg-emerald-600 text-white' : ($active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500') }}">
        {{ $id }}
      </span>
      <span class="{{ $active ? 'font-semibold text-neutral-900 dark:text-white' : 'text-neutral-500' }}">{{ $label }}</span>
      @if(!$loop->last)
        <span class="mx-2 h-px w-8 bg-neutral-200 dark:bg-neutral-700"></span>
      @endif
    </li>
  @endforeach
</ol>
