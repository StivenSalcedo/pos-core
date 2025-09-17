<label {{ $attributes->class([
    'block text-sm font-semibold',
    'text-red-600'  => $hasError,
    'opacity-60'         => $attributes->get('disabled'),
    'text-gray-700' => !$hasError,
]) }}>
{{ $label ?? $slot }}
</label>
