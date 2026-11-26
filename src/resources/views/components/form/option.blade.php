@props([
    'type' => 'radio',
    'name',
    'value',
    'checked' => false,
])

<label class="opt">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($checked)
        {{ $attributes }}
    >
    <span>{{ $slot }}</span>
</label>
