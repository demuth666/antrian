@props([
    'nomorTampil',
    'nama',
    'layanan',
    'tanggal',
    'status',
    'statusPesan',
    'sisaTeks' => '',
    'qrSvg',
])

@php
    $kelasStatus = match ($status) {
        \App\Enums\StatusAntrian::Dipanggil => 'warn',
        \App\Enums\StatusAntrian::Dilayani, \App\Enums\StatusAntrian::Selesai => 'ok',
        \App\Enums\StatusAntrian::TidakHadir => 'bad',
        \App\Enums\StatusAntrian::Batal => 'dim',
        default => '',
    };
@endphp

<article class="ticket-paper" aria-label="{{ __('queue.ticket.aria') }}">
    <div class="ticket-main">
        <div class="ticket-head">
            <span class="ticket-brand">{{ __('common.brand') }}</span>
            <span class="ticket-stamp" aria-hidden="true">{{ __('queue.ticket.priority') }}</span>
        </div>

        <p class="ticket-label">{{ __('queue.ticket.number_label') }}</p>
        <p class="ticket-number" id="q-num">{{ $nomorTampil }}</p>

        <div class="ticket-status">
            <span @class(['stat-t', $kelasStatus => $kelasStatus !== '']) id="status-pill">{{ $status->label() }}</span>
            <span id="q-sisa">{{ $sisaTeks }}</span>
        </div>

        <p id="status-pesan" class="ticket-message" role="status" aria-live="polite">{{ $statusPesan }}</p>

        <dl class="ticket-meta">
            <div>
                <dt>{{ __('queue.ticket.name') }}</dt>
                <dd id="q-nama">{{ $nama }}</dd>
            </div>
            <div>
                <dt>{{ __('queue.ticket.service') }}</dt>
                <dd id="q-layanan">{{ $layanan }}</dd>
            </div>
            <div class="ticket-meta-date">
                <dt>{{ __('queue.ticket.date') }}</dt>
                <dd id="q-tgl">{{ $tanggal }}</dd>
            </div>
        </dl>
    </div>

    <div class="ticket-stub">
        <div class="ticket-qr" id="qr" role="img" aria-label="{{ __('queue.ticket.qr_aria', ['number' => $nomorTampil]) }}">{!! $qrSvg !!}</div>
        <p class="ticket-scan">{{ __('queue.ticket.scan') }}</p>
    </div>
</article>
