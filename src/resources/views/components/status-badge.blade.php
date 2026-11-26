@props([
    'status',
])

@php
    $kelas = match ($status) {
        \App\Enums\StatusAntrian::Dipanggil => 'warn',
        \App\Enums\StatusAntrian::Dilayani, \App\Enums\StatusAntrian::Selesai => 'ok',
        \App\Enums\StatusAntrian::TidakHadir => 'bad',
        \App\Enums\StatusAntrian::Batal => 'dim',
        default => '',
    };
@endphp

<span @class(['stat-t kecil', $kelas => $kelas !== ''])>{{ $status->label() }}</span>
