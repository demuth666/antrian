@php
    $instansi = config('layanan.instansi.nama');
    $instansi = $instansi && ! str_starts_with($instansi, '[') ? $instansi : null;

    $telepon = config('layanan.instansi.telepon');
    $telepon = $telepon && ! str_starts_with($telepon, '[') ? $telepon : null;
@endphp

<footer>
    <div class="wrap">
        <span>
            &copy; {{ now()->year }} {{ __('common.brand') }}@if ($instansi) &middot; {{ $instansi }}@endif
        </span>

        <span class="footer-links">
            @if ($telepon)
                <a href="tel:{{ preg_replace('/\s+/', '', $telepon) }}">{{ $telepon }}</a>
            @endif
            <a href="#top">{{ __('common.back_to_top') }}</a>
        </span>
    </div>
</footer>
