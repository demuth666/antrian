<x-layout.app
    :title="__('queue.show.title', ['number' => $antrian->nomor_tampil])"
    :description="__('queue.show.description')"
    :skip-text="__('common.skip_to_content')"
    font-set="public"
    body-class="landing"
    robots="noindex"
>
    <x-site-header>
        <div class="ticket-header-actions">
            <x-ui.button
                type="button"
                variant="accent"
                size="sm"
                data-unduh-tiket
                data-i18n="{{ json_encode([
                    'download' => __('queue.show.download'),
                    'priority' => __('queue.ticket.priority'),
                    'ticket_label' => __('queue.js.ticket_label'),
                    'number_label' => __('queue.js.number_label'),
                    'scan' => __('queue.js.scan'),
                    'name' => __('queue.js.name'),
                    'service' => __('queue.js.service'),
                    'date' => __('queue.js.date'),
                    'show' => __('queue.js.show'),
                    'save' => __('queue.js.save'),
                    'preparing' => __('queue.js.preparing'),
                    'downloaded' => __('queue.js.downloaded'),
                    'failed' => __('queue.js.failed'),
                    'filename' => __('queue.js.filename'),
                ], JSON_UNESCAPED_UNICODE) }}"
            >{{ __('queue.show.download') }}</x-ui.button>
            <x-ui.button :href="route('register')" variant="outline" size="sm">{{ __('queue.show.new_registration') }}</x-ui.button>
        </div>
    </x-site-header>

    @if (session('status'))
        <x-notice-bar role="status">{{ session('status') }}</x-notice-bar>
    @endif

    <main
        id="isi"
        class="wrap ticket-page"
        data-antrian
        data-kode="{{ $antrian->kode }}"
        data-poll-url="{{ route('api.queue.now-serving', ['kode' => $antrian->kode]) }}"
        data-terminal="{{ $antrian->status->terminal() ? '1' : '0' }}"
    >
        <header class="ticket-intro">
            <div class="ticket-intro-head">
                <x-ui.kicker>{{ __('queue.show.kicker') }}</x-ui.kicker>
                <h1>{{ __('queue.show.heading') }}</h1>
            </div>

            <p class="lead">{{ __('queue.show.lead') }}</p>
        </header>

        <x-antrian.ticket
            :nomor-tampil="$antrian->nomor_tampil"
            :nama="$antrian->nama_samar"
            :layanan="$antrian->layanan->nama"
            :tanggal="$antrian->tanggal->locale(app()->getLocale())->translatedFormat('l, j F Y')"
            :status="$antrian->status"
            :status-pesan="$statusPesan"
            :sisa-teks="$sisaTeks"
            :qr-svg="$qrSvg"
        />

        <x-antrian.review :antrian="$antrian" :open="session('buka_ulasan', false)" />

        <x-antrian.now-serving :lokets="$lokets" />
    </main>

    <x-site-footer />
</x-layout.app>
