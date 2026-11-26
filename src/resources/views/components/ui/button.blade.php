@props([
    'href' => null,
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
])

@php
    $classes = ['btn'];

    if ($variant === 'accent') {
        $classes[] = 'acc';
    } elseif ($variant === 'outline') {
        $classes[] = 'out';
    } elseif ($variant === 'danger') {
        $classes[] = 'danger';
    }

    if ($size === 'sm') {
        $classes[] = 'sm';
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
