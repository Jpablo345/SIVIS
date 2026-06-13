@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-red-400']) }}>
    {{ $value ?? $slot }}
</label>
