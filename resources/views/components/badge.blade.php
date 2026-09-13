@props(['tone' => 'default'])

@php
    $toneClasses =
        [
            'success' => 'bg-success/10 text-success',
            'danger' => 'bg-danger/10 text-danger',
            'warn' => 'bg-warn/10 text-warn',
            'primary' => 'bg-primary-tint text-primary',
            'default' => 'bg-ink-muted/10 text-ink-muted',
        ][$tone] ?? 'bg-ink-muted/10 text-ink-muted';
@endphp

<span {{ $attributes->merge(['class' => "inline-block px-2 py-0.5 text-xs font-medium rounded-full $toneClasses"]) }}>
    {{ $slot }}
</span>
