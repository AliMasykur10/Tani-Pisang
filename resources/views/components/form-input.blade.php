@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label class="mb-1 block text-sm font-medium text-ink" for="{{ $name }}">{{ $label }}</label>
    <input {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-line bg-surface text-ink text-sm focus:border-primary focus:ring-primary']) }}
        id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}">
    @error($name)
        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
    @enderror
</div>
