@props(['accent' => 'line'])

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

<div
    {{ $attributes->merge(['class' => 'bg-surface rounded-xl border border-line p-4 flex gap-3 hover:shadow-sm transition-shadow']) }}>
    <div class="{{ $accentClasses }} w-1 flex-shrink-0 rounded-full"></div>
    <div class="min-w-0 flex-1">
        {{ $slot }}
    </div>
</div>
