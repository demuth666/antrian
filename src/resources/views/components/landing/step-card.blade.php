@props(['number', 'title', 'total' => '03'])

<div {{ $attributes->class('step-item') }}>
    <span class="step-no">{{ $number }} / {{ $total }}</span>
    <h3>{{ $title }}</h3>

    {{ $slot }}

    @isset($actions)
        <div class="cta">{{ $actions }}</div>
    @endisset
</div>
