@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-body-secondary']) }}>
    {{ $value ?? $slot }}
</label>
