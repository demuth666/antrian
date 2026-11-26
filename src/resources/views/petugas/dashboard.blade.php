<x-layout.panel :title="__('officer.dashboard.title')">
    <div class="panel-head">
        <div>
            <x-ui.kicker>{{ $petugas->loket?->nama ?? __('officer.dashboard.kicker') }}</x-ui.kicker>
            <h1>{{ __('officer.dashboard.title') }}</h1>
            <p class="muted">
                {{ now()->locale(app()->getLocale())->translatedFormat('l, j F Y') }}
                <span aria-hidden="true">·</span>
                {{ trans_choice('officer.dashboard.total_today', $antrians->count(), ['total' => $antrians->count()]) }}
            </p>
        </div>

        <div class="cta">
            @if ($petugas->loket && $layanans->isNotEmpty())
                <form method="POST" action="{{ route('officer.queue.call-next') }}">
                    @csrf
                    <x-ui.button
                        type="submit"
                        variant="accent"
                        :disabled="$jumlahMenunggu === 0"
                    >{{ __('officer.dashboard.call_next') }}</x-ui.button>
                </form>
            @endif

            <x-ui.button :href="route('officer.find-ticket')" variant="outline">{{ __('officer.dashboard.find_ticket') }}</x-ui.button>
        </div>
    </div>

    @if (! $petugas->loket)
        <div class="alert bad" role="alert">
            {{ __('officer.dashboard.no_counter') }}
        </div>
    @elseif ($layanans->isEmpty())
        <div class="alert bad" role="alert">
            {{ __('officer.dashboard.no_services', ['counter' => $petugas->loket->nama]) }}
        </div>
    @elseif ($antrians->isEmpty())
        <div class="desk-empty-page">
            <div class="desk-empty-stub" aria-hidden="true">&mdash;</div>
            <div class="desk-empty-copy">
                <h2>{{ __('officer.dashboard.empty_page_title') }}</h2>
                <p>{{ __('officer.dashboard.empty_page_body') }}</p>
            </div>
        </div>
    @else
        <div class="desk-layout">
            <div class="desk-main">
                <section class="desk-section" aria-labelledby="di-loket">
                    <div class="desk-section-head">
                        <div class="desk-section-title">
                            <h2 id="di-loket">{{ __('officer.dashboard.active_title') }}</h2>
                            <span class="desk-count">{{ $sedangDilayani->count() }}</span>
                        </div>
                    </div>

                    @if ($sedangDilayani->isEmpty())
                        <div class="desk-desk-empty">
                            <p>{{ __('officer.dashboard.empty_desk') }}</p>
                        </div>
                    @else
                        @foreach ($sedangDilayani as $antrian)
                            <article class="desk-ticket" aria-label="{{ $antrian->nomor_tampil }} · {{ $antrian->pendaftar->nama }}">
                                <div class="desk-ticket-stub">
                                    <x-status-badge :status="$antrian->status" />
                                    <strong class="desk-ticket-number">{{ $antrian->nomor_tampil }}</strong>
                                    <span class="desk-ticket-time">
                                        @if ($antrian->status === \App\Enums\StatusAntrian::Dipanggil)
                                            {{ __('officer.dashboard.called_at', ['time' => ($antrian->dipanggil_at ?? $antrian->created_at)->format('H:i')]) }}
                                        @else
                                            {{ __('officer.dashboard.serving_since', ['time' => ($antrian->mulai_dilayani_at ?? $antrian->updated_at)->format('H:i')]) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="desk-ticket-body">
                                    <div class="desk-ticket-head">
                                        <div class="desk-ticket-person">
                                            <h3>{{ $antrian->pendaftar->nama }}</h3>
                                            <p>
                                                {{ $antrian->layanan->nama }}
                                                <span aria-hidden="true">·</span>
                                                @if ($antrian->pendaftar->jenis_disabilitas)
                                                    {{ __('enums.disability_types.'.$antrian->pendaftar->jenis_disabilitas) }}
                                                @else
                                                    {{ $antrian->kategori->label() }}
                                                @endif
                                            </p>
                                        </div>

                                        <div class="desk-ticket-actions">
                                            @if ($antrian->status === \App\Enums\StatusAntrian::Dipanggil)
                                                <form method="POST" action="{{ route('officer.queue.start', ['antrian' => $antrian]) }}">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="accent" aria-label="{{ __('officer.dashboard.start') }} {{ $antrian->nomor_tampil }}">{{ __('officer.dashboard.start') }}</x-ui.button>
                                                </form>

                                                <form method="POST" action="{{ route('officer.queue.mark-absent', ['antrian' => $antrian]) }}">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="outline" size="sm" aria-label="{{ __('officer.dashboard.absent') }} {{ $antrian->nomor_tampil }}">{{ __('officer.dashboard.absent') }}</x-ui.button>
                                                </form>

                                                <form method="POST" action="{{ route('officer.queue.cancel', ['antrian' => $antrian]) }}">
                                                    @csrf
                                                    <button type="submit" class="desk-text-action is-danger" aria-label="{{ __('officer.dashboard.cancel') }} {{ $antrian->nomor_tampil }}">{{ __('officer.dashboard.cancel') }}</button>
                                                </form>
                                            @elseif ($antrian->status === \App\Enums\StatusAntrian::Dilayani)
                                                <form method="POST" action="{{ route('officer.queue.finish', ['antrian' => $antrian]) }}">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="accent" aria-label="{{ __('officer.dashboard.finish') }} {{ $antrian->nomor_tampil }}">{{ __('officer.dashboard.finish') }}</x-ui.button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <dl class="desk-ticket-meta">
                                        <div>
                                            <dt>{{ __('officer.dashboard.needs') }}</dt>
                                            <dd>
                                                @if ($antrian->bantuans->isNotEmpty())
                                                    {{ $antrian->bantuans->pluck('nama')->join(', ') }}
                                                @else
                                                    {{ __('officer.dashboard.needs_none') }}
                                                @endif
                                            </dd>
                                        </div>

                                        @if ($antrian->nama_pendamping)
                                            <div>
                                                <dt>{{ __('officer.dashboard.companion') }}</dt>
                                                <dd>{{ $antrian->nama_pendamping }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </section>

                <section class="desk-section" aria-labelledby="menunggu">
                    <div class="desk-section-head">
                        <div class="desk-section-title">
                            <h2 id="menunggu">{{ __('officer.dashboard.waiting_title') }}</h2>
                            <span class="desk-count">{{ $jumlahMenunggu }}</span>
                        </div>

                        @if ($menunggu->isNotEmpty())
                            <p class="desk-section-note">{{ __('officer.dashboard.arrival_order') }}</p>
                        @endif
                    </div>

                    @if ($menunggu->isEmpty())
                        <p class="desk-empty">{{ __('officer.dashboard.waiting_empty') }}</p>
                    @else
                        <div class="table-wrap desk-table-wrap">
                            <table class="data-table is-stacked desk-table desk-table-waiting" aria-label="{{ __('officer.dashboard.table_aria') }}">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('officer.dashboard.headers.queue') }}</th>
                                        <th scope="col">{{ __('officer.dashboard.headers.resident') }}</th>
                                        <th scope="col">{{ __('officer.dashboard.headers.since') }}</th>
                                        <th scope="col">{{ __('officer.dashboard.headers.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menunggu as $antrian)
                                        <tr>
                                            <td class="desk-cell-number" data-label="{{ __('officer.dashboard.headers.queue') }}">
                                                <span class="desk-number-group">
                                                    <span class="desk-position">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                    <strong class="desk-number">{{ $antrian->nomor_tampil }}</strong>
                                                </span>
                                            </td>

                                            <td class="desk-cell-resident cell-primary">
                                                <span class="desk-name">{{ $antrian->pendaftar->nama }}</span>
                                                <span class="desk-meta">
                                                    {{ $antrian->layanan->nama }}
                                                    <span aria-hidden="true">·</span>
                                                    @if ($antrian->pendaftar->jenis_disabilitas)
                                                        {{ __('enums.disability_types.'.$antrian->pendaftar->jenis_disabilitas) }}
                                                    @else
                                                        {{ $antrian->kategori->label() }}
                                                    @endif
                                                </span>
                                                <span class="desk-needs">
                                                    {{ __('officer.dashboard.needs') }}:
                                                    @if ($antrian->bantuans->isNotEmpty())
                                                        {{ $antrian->bantuans->pluck('nama')->join(', ') }}
                                                    @else
                                                        {{ __('officer.dashboard.needs_none') }}
                                                    @endif
                                                </span>
                                            </td>

                                            <td class="desk-cell-time" data-label="{{ __('officer.dashboard.headers.since') }}">
                                                <time datetime="{{ $antrian->created_at->toIso8601String() }}">{{ $antrian->created_at->format('H:i') }}</time>
                                            </td>

                                            <td class="desk-cell-actions cell-actions" data-label="{{ __('officer.dashboard.headers.actions') }}">
                                                <form method="POST" action="{{ route('officer.queue.cancel', ['antrian' => $antrian]) }}">
                                                    @csrf
                                                    <button type="submit" class="desk-text-action is-danger" aria-label="{{ __('officer.dashboard.cancel') }} {{ $antrian->nomor_tampil }}">{{ __('officer.dashboard.cancel') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>

            <aside class="desk-aside">
                <section class="desk-section" aria-labelledby="ditutup">
                    <div class="desk-section-head">
                        <div class="desk-section-title">
                            <h2 id="ditutup">{{ __('officer.dashboard.closed_title') }}</h2>
                            <span class="desk-count">{{ $ditutup->count() }}</span>
                        </div>
                    </div>

                    @if ($ditutup->isEmpty())
                        <p class="desk-empty">{{ __('officer.dashboard.closed_empty') }}</p>
                    @else
                        <ol class="desk-closed">
                            @foreach ($ditutup as $antrian)
                                @php($waktuTutup = $antrian->selesai_at ?? $antrian->updated_at)
                                <li class="desk-closed-row">
                                    <div class="desk-closed-head">
                                        <strong>{{ $antrian->nomor_tampil }}</strong>
                                        <span title="{{ $antrian->pendaftar->nama }}">{{ $antrian->pendaftar->nama }}</span>
                                    </div>
                                    <div class="desk-closed-meta">
                                        <x-status-badge :status="$antrian->status" />
                                        <time datetime="{{ $waktuTutup->toIso8601String() }}">{{ $waktuTutup->format('H:i') }}</time>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </aside>
        </div>
    @endif
</x-layout.panel>
