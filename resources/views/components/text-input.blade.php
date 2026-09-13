@props(['disabled' => false])

<input
    {{ $attributes->merge(['class' => 'border-line bg-surface text-ink rounded-lg shadow-sm focus:border-primary focus:ring-primary']) }}
    @disabled($disabled)>
