@props([
    'label' => null,
    'for' => null,
    'hint' => null,
    'full' => false,
    'hidden' => false,
    'errorKey' => null,
])

@php
    $errorKey ??= $for;
@endphp

<div {{ $attributes->class(['field', 'full' => $full, 'hidden' => $hidden, 'has-error' => $errorKey && $errors->has($errorKey)]) }}>
    @if ($label)
        <label class="lab" for="{{ $for }}">
            {{ $label }}@if ($hint) <small>({{ $hint }})</small>@endif
        </label>
    @endif

    {{ $slot }}

    @if ($errorKey && $errors->has($errorKey))
        <span class="err" role="alert">{{ $errors->first($errorKey) }}</span>
    @endif
</div>
