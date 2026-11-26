@props(['icon', 'title'])

<div {{ $attributes->class('audience-row') }}>
    <x-icon :name="$icon" />

    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $slot }}</p>
    </div>
</div>
