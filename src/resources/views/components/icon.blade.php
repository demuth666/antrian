@props(['name'])

<svg {{ $attributes->class('i') }} aria-hidden="true"><use href="#{{ $name }}" /></svg>
