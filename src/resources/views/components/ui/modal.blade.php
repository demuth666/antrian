@props([
    'id',
    'title',
    'description' => null,
    'variant' => 'info',
])

@php
    $ikon = match ($variant) {
        'success' => 'check',
        'danger', 'warning' => 'alert',
        default => 'info',
    };

    $warna = match ($variant) {
        'success' => 'ok',
        'danger' => 'bad',
        'warning' => 'warn',
        default => '',
    };

    $judulId = $id.'-judul';
    $teksId = $id.'-teks';
@endphp

<dialog
    id="{{ $id }}"
    {{ $attributes->class('modal') }}
    aria-labelledby="{{ $judulId }}"
    @if ($description) aria-describedby="{{ $teksId }}" @endif
>
    <div class="modal-card">
        <button class="modal-close" type="button" data-modal-close aria-label="{{ __('common.close') }}" autofocus>
            <x-icon name="close" />
        </button>

        <span @class(['modal-icon', $warna])>
            <x-icon :name="$ikon" />
        </span>

        <h2 class="modal-title" id="{{ $judulId }}">{{ $title }}</h2>

        @if ($description)
            <p class="modal-text" id="{{ $teksId }}">{{ $description }}</p>
        @endif

        {{ $slot }}
    </div>
</dialog>
