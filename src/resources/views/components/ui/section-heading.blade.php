@props(['kicker' => null])

<div {{ $attributes->class('head') }}>
    @if ($kicker)
        <x-ui.kicker>{{ $kicker }}</x-ui.kicker>
    @endif

    <h2>{{ $slot }}</h2>
</div>
