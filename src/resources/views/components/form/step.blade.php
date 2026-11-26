@props([
    'number',
    'title',
    'description' => null,
    'headingId' => null,
])

<section @if ($headingId) aria-labelledby="{{ $headingId }}" @endif {{ $attributes->class('form-step') }}>
    <div class="form-step-head">
        <span class="form-step-no" aria-hidden="true">{{ $number }}</span>
        <h2 @if ($headingId) id="{{ $headingId }}" @endif>{{ $title }}</h2>

        @if ($description)
            <p class="form-step-sub">{{ $description }}</p>
        @endif
    </div>

    <div class="form-step-body">
        {{ $slot }}
    </div>
</section>
