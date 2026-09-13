@props(['accent' => 'line', 'date' => null])

@php
    $accentClasses =
        [
            'success' => 'bg-success',
            'danger' => 'bg-danger',
            'warn' => 'bg-warn',
            'primary' => 'bg-primary',
            'line' => 'bg-line',
        ][$accent] ?? 'bg-line';
@endphp

<div class="flex gap-4">
    <div class="flex w-2 flex-shrink-0 flex-col items-center">
        <div class="{{ $accentClasses }} mt-1.5 h-2.5 w-2.5 rounded-full"></div>
        <div class="mt-1 w-px flex-1 bg-line"></div>
    </div>
    <div class="min-w-0 flex-1 pb-6">
        @if ($date)
            <p class="mb-1 text-xs text-ink-muted">{{ $date }}</p>
        @endif
        <div class="rounded-xl border border-line bg-surface p-4">
            {{ $slot }}
        </div>
    </div>
</div>
