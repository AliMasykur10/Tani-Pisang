@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
   {{ $active ? 'bg-primary-tint text-primary font-medium' : 'text-ink-muted hover:bg-bg hover:text-ink' }}">
    <span class="flex-shrink-0">{{ $icon }}</span>
    <span>{{ $slot }}</span>
</a>