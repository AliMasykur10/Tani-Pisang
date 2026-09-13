@props(['label', 'name', 'options' => [], 'selected' => null, 'placeholder' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-ink mb-1">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-line bg-surface text-ink text-sm focus:border-primary focus:ring-primary']) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    @error($name)
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>