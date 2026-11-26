<x-layout.panel :title="__('admin.dashboard.title')">
    <div class="panel-head">
        <div>
            <h1>{{ __('admin.dashboard.heading') }}</h1>
            <p class="lead">{{ __('admin.dashboard.lead') }}</p>
        </div>
    </div>

    <div class="overview-layout">
        <section class="overview-sheet" aria-labelledby="data-dasar">
            <header class="overview-sheet-head">
                <h2 id="data-dasar">{{ __('admin.dashboard.data_title') }}</h2>
                <span>{{ __('admin.dashboard.entries', ['count' => $jumlahLayanan + $jumlahLoket + $jumlahBantuan + $jumlahPetugas]) }}</span>
            </header>

            <a class="overview-row" href="{{ route('admin.services.index') }}">
                <x-icon name="layers" />
                <span class="overview-row-label">
                    <strong>{{ __('admin.dashboard.services') }}</strong>
                    <span>{{ __('admin.dashboard.active', ['count' => $jumlahLayananAktif]) }}</span>
                </span>
                <span class="overview-row-value">{{ $jumlahLayanan }}</span>
                <x-icon name="chevron-right" />
            </a>

            <a class="overview-row" href="{{ route('admin.counters.index') }}">
                <x-icon name="door" />
                <span class="overview-row-label">
                    <strong>{{ __('admin.dashboard.counters') }}</strong>
                    <span>{{ __('admin.dashboard.active', ['count' => $jumlahLoketAktif]) }}</span>
                </span>
                <span class="overview-row-value">{{ $jumlahLoket }}</span>
                <x-icon name="chevron-right" />
            </a>

            <a class="overview-row" href="{{ route('admin.assistance.index') }}">
                <x-icon name="heart" />
                <span class="overview-row-label">
                    <strong>{{ __('admin.dashboard.assistance') }}</strong>
                    <span>{{ __('admin.dashboard.assistance_hint') }}</span>
                </span>
                <span class="overview-row-value">{{ $jumlahBantuan }}</span>
                <x-icon name="chevron-right" />
            </a>

            <a class="overview-row" href="{{ route('admin.officers.index') }}">
                <x-icon name="users" />
                <span class="overview-row-label">
                    <strong>{{ __('admin.dashboard.officers') }}</strong>
                    <span>{{ __('admin.dashboard.active', ['count' => $jumlahPetugasAktif]) }}</span>
                </span>
                <span class="overview-row-value">{{ $jumlahPetugas }}</span>
                <x-icon name="chevron-right" />
            </a>

            <div class="overview-perf" aria-hidden="true"></div>
        </section>

        <aside class="quick-sheet" aria-labelledby="tambah-data">
            <h2 id="tambah-data">{{ __('admin.dashboard.quick_title') }}</h2>

            <div class="quick-list">
                <a href="{{ route('admin.services.create') }}">
                    <x-icon name="plus" />
                    <span>{{ __('admin.services.add') }}</span>
                    <x-icon name="chevron-right" />
                </a>

                <a href="{{ route('admin.counters.create') }}">
                    <x-icon name="plus" />
                    <span>{{ __('admin.counters.add') }}</span>
                    <x-icon name="chevron-right" />
                </a>

                <a href="{{ route('admin.assistance.create') }}">
                    <x-icon name="plus" />
                    <span>{{ __('admin.assistance.add') }}</span>
                    <x-icon name="chevron-right" />
                </a>

                <a href="{{ route('admin.officers.create') }}">
                    <x-icon name="plus" />
                    <span>{{ __('admin.officers.add') }}</span>
                    <x-icon name="chevron-right" />
                </a>
            </div>
        </aside>
    </div>

    <section class="review-overview" aria-labelledby="kepuasan">
        <div class="review-overview-head">
            <div>
                <h2 id="kepuasan">{{ __('admin.dashboard.review_title') }}</h2>
                <p>{{ __('admin.dashboard.review_lead') }}</p>
            </div>
            <span>{{ trans_choice('admin.dashboard.review_count', $jumlahUlasan, ['count' => $jumlahUlasan]) }}</span>
        </div>

        @if ($jumlahUlasan === 0)
            <p class="empty">{{ __('admin.dashboard.review_empty') }}</p>
        @else
            <div class="review-overview-body">
                <div class="review-overview-score">
                    <span>{{ __('admin.dashboard.review_average') }}</span>
                    <strong>{{ number_format($rataRataUlasan, 1) }}</strong>
                    <span>{{ __('admin.dashboard.review_out_of') }}</span>

                    <span class="review-overview-stars" role="img" aria-label="{{ __('admin.dashboard.review_stars_aria', ['rating' => number_format($rataRataUlasan, 1)]) }}">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-icon name="star" @class(['is-filled' => $i <= round($rataRataUlasan)]) />
                        @endfor
                    </span>
                </div>

                <div class="review-overview-latest">
                    <h3>{{ __('admin.dashboard.review_latest') }}</h3>

                    <ul>
                        @foreach ($ulasanTerbaru as $ulasan)
                            <li>
                                <div class="review-overview-meta">
                                    <span class="review-overview-stars" role="img" aria-label="{{ __('admin.dashboard.review_stars_aria', ['rating' => $ulasan->rating]) }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <x-icon name="star" @class(['is-filled' => $i <= $ulasan->rating]) />
                                        @endfor
                                    </span>
                                    <span>{{ $ulasan->antrian?->layanan?->nama }}</span>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>{{ $ulasan->antrian?->pendaftar?->nama_samar }}</span>
                                    <time datetime="{{ $ulasan->created_at->toIso8601String() }}">{{ $ulasan->created_at->locale(app()->getLocale())->translatedFormat('j M H:i') }}</time>
                                </div>
                                <p>{{ $ulasan->komentar ?: __('admin.dashboard.review_no_comment') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </section>
</x-layout.panel>
