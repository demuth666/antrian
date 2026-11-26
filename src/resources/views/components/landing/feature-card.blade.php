@props(['icon', 'title'])

<div {{ $attributes->class('feature') }}>
    <x-icon :name="$icon" />

    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $slot }}</p>
    </div>
</div>
