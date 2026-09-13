@php
    use App\Support\ActiveLahan;
    $allLahans = \App\Models\Lahan::all();
    $active = ActiveLahan::get();
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center gap-2 rounded-lg bg-primary-tint px-3 py-1.5 text-sm font-medium text-primary">
        <span>{{ $active ? $active->nama : 'Semua Lahan' }}</span>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
    </button>

    <div @click.away="open = false"
        class="absolute left-0 z-50 mt-2 w-56 rounded-lg border border-line bg-surface py-1 shadow-lg" x-cloak
        x-show="open">

        @if (auth()->user()->role === 'admin')
            <form action="{{ route('lahan-picker.select-all') }}" method="POST">
                @csrf
                <input name="redirect_to" type="hidden" value="{{ url()->current() }}">
                <button
                    class="{{ !$active ? 'font-medium text-primary' : 'text-ink' }} w-full px-4 py-2 text-left text-sm hover:bg-primary-tint"
                    type="submit">
                    Semua Lahan (Konsolidasi)
                </button>
            </form>
            <div class="my-1 border-t border-line"></div>
        @endif

        @foreach ($allLahans as $lahan)
            <form action="{{ route('lahan-picker.select', $lahan) }}" method="POST">
                @csrf
                <input name="redirect_to" type="hidden" value="{{ url()->current() }}">
                <button
                    class="{{ $active?->id === $lahan->id ? 'font-medium text-primary' : 'text-ink' }} w-full px-4 py-2 text-left text-sm hover:bg-primary-tint"
                    type="submit">
                    {{ $lahan->nama }}
                </button>
            </form>
        @endforeach
    </div>
</div>
