<x-layout.panel :title="__('officer.find.title')">
    <div class="panel-head">
        <div>
            <x-ui.kicker>{{ auth()->user()?->loket?->nama ?? __('officer.find.kicker') }}</x-ui.kicker>
            <h1>{{ __('officer.find.heading') }}</h1>
            <p class="muted">{{ __('officer.find.description') }}</p>
        </div>

        <div class="cta">
            <x-ui.button :href="route('officer.dashboard')" variant="outline">
                <x-icon name="arrow-left" />
                {{ __('officer.find.back') }}
            </x-ui.button>
        </div>
    </div>

    <div class="form-sheet desk-find-sheet">
        <form method="POST" action="{{ route('officer.find-ticket.store') }}">
            @csrf

            <x-form.field :label="__('registration.identity.nik')" :hint="__('officer.find.nik_hint')" for="nik">
                <input
                    class="in"
                    id="nik"
                    type="text"
                    name="nik"
                    inputmode="numeric"
                    pattern="[0-9]{16}"
                    maxlength="16"
                    autocomplete="off"
                    required
                    autofocus
                >
            </x-form.field>

            <p class="form-note">
                {{ __('officer.find.note') }}
            </p>

            <div class="form-actions">
                <x-ui.button type="submit" variant="accent">{{ __('officer.find.submit') }}</x-ui.button>
            </div>
        </form>
    </div>

    @if ($hasil !== null)
        <section class="desk-section desk-find-results" aria-labelledby="hasil-pencarian">
            <div class="desk-section-head">
                <div class="desk-section-title">
                    <h2 id="hasil-pencarian">{{ __('officer.find.result_title') }}</h2>
                    <span class="desk-count">{{ $hasil->count() }}</span>
                </div>
            </div>

            @if ($hasil->isEmpty())
                <div class="desk-notice" role="status">
                    <x-icon name="info" />
                    <p>{{ __('officer.find.empty') }}</p>
                </div>
            @else
                <div class="table-wrap desk-table-wrap">
                    <table class="data-table is-stacked desk-table" aria-label="{{ __('officer.find.table_aria') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('officer.find.headers.queue') }}</th>
                                <th scope="col">{{ __('officer.find.headers.service') }}</th>
                                <th scope="col">{{ __('officer.find.headers.status') }}</th>
                                <th scope="col">{{ __('officer.find.headers.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasil as $antrian)
                                <tr>
                                    <td class="desk-cell-find cell-primary">
                                        <strong class="desk-number">{{ $antrian->nomor_tampil }}</strong>
                                        <span class="desk-name">{{ $antrian->pendaftar?->nama }}</span>
                                    </td>

                                    <td class="desk-cell-resident desk-cell-block">
                                        <span class="desk-stack">
                                            <span class="desk-name">{{ $antrian->layanan?->nama }}</span>
                                            @if ($antrian->loket)
                                                <span class="desk-meta">{{ $antrian->loket->nama }}</span>
                                            @endif
                                        </span>
                                    </td>

                                    <td class="desk-cell-status" data-label="{{ __('officer.find.headers.status') }}">
                                        <x-status-badge :status="$antrian->status" />
                                    </td>

                                    <td class="desk-cell-actions cell-actions" data-label="{{ __('officer.find.headers.actions') }}">
                                        <x-ui.button :href="route('queue.show', ['antrian' => $antrian])" variant="outline" size="sm" aria-label="{{ __('officer.find.open') }} {{ $antrian->nomor_tampil }}">
                                            {{ __('officer.find.open') }}
                                        </x-ui.button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endif
</x-layout.panel>
