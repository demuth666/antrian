@props([
    'variant' => 'success',
    'title' => null,
])

@php
    $berhasil = $variant === 'success';
    $ikon = $berhasil ? 'check' : 'alert';
    $warna = $berhasil ? 'ok' : 'bad';
@endphp

<div
    class="toast {{ $warna }}"
    role="{{ $berhasil ? 'status' : 'alert' }}"
    data-toast
>
    <span class="toast-icon {{ $warna }}">
        <x-icon :name="$ikon" />
    </span>

    <div class="toast-body">
        @if ($title)
            <strong>{{ $title }}</strong>
        @endif

        <p>{{ $slot }}</p>
    </div>

    <button class="toast-close" type="button" data-toast-close aria-label="{{ __('common.close_notification') }}">
        <x-icon name="close" />
    </button>
</div>
