<div
    class="note noprint"
    data-antrian-banner
    data-api-url="{{ route('api.queue.now-serving') }}"
    data-tiket-url="{{ route('queue.show', ['antrian' => '__KODE__']) }}"
    role="status"
    aria-live="polite"
    hidden
>
    <span>{{ __('landing.banner.active') }}<span data-antrian-banner-nomor></span>{{ __('landing.banner.separator') }}</span>
    <a href="{{ route('check-queue') }}" data-antrian-banner-link>{{ __('landing.banner.link') }}</a>
</div>
