@props(['label', 'name', 'value' => null, 'rows' => 3])

<div>
    <label class="mb-1 block text-sm font-medium text-ink" for="{{ $name }}">{{ $label }}</label>
    <textarea
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-line bg-surface text-ink text-sm focus:border-primary focus:ring-primary']) }}
        id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}">{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
    @enderror
</div>
