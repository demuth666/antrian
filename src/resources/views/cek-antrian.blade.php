<x-layout.app
    :title="__('queue.check.title')"
    :description="__('queue.check.description')"
    :skip-text="__('common.skip_to_form')"
    font-set="public"
    body-class="landing check-body"
>
    <x-site-header
        :links="[
            ['label' => __('common.home'), 'href' => route('home')],
        ]"
    >
        <x-ui.button :href="route('register')" variant="accent" size="sm">{{ __('queue.check.new_registration') }}</x-ui.button>
    </x-site-header>

    @php
        $instansi = config('layanan.instansi.nama');
        $instansi = $instansi && ! str_starts_with($instansi, '[') ? $instansi : null;

        $telepon = config('layanan.instansi.telepon');
        $telepon = $telepon && ! str_starts_with($telepon, '[') ? $telepon : null;
    @endphp

    <main id="isi" class="wrap check-page">
        <header class="check-intro">
            <div class="check-intro-head">
                <x-ui.kicker>{{ __('queue.check.kicker') }}</x-ui.kicker>
                <h1>{{ __('queue.check.heading_before') }}<span class="hl">{{ __('queue.check.heading_highlight') }}</span>{{ __('queue.check.heading_after') }}</h1>
            </div>

            <div class="check-intro-aside">
                <p class="lead">{{ __('queue.check.lead') }}</p>
                <p class="check-meta">{{ __('queue.check.meta') }}</p>
            </div>
        </header>

        <div class="check-sheet">
            <div class="check-main">
                <div class="check-sheet-head">
                    <span>{{ __('queue.check.form_label') }}</span>
                    <span class="check-sheet-date">{{ now()->locale(app()->getLocale())->translatedFormat('j F Y') }}</span>
                    <span class="check-stamp" aria-hidden="true">{{ __('queue.check.stamp') }}</span>
                </div>

                <form class="check-form" method="POST" action="{{ route('check-queue.store') }}">
                    @csrf

                    @if ($errors->has('cek_antrian'))
                        <div class="check-alert" role="alert">
                            <x-icon name="alert" />
                            <p>{{ $errors->first('cek_antrian') }}</p>
                        </div>
                    @endif

                    <div class="check-fields">
                        <x-form.field :label="__('registration.identity.nik')" :hint="__('queue.check.nik_hint')" for="nik">
                            <input
                                class="in"
                                id="nik"
                                type="text"
                                name="nik"
                                inputmode="numeric"
                                pattern="[0-9]{16}"
                                maxlength="16"
                                autocomplete="off"
                                value="{{ old('nik') }}"
                                required
                            >
                        </x-form.field>

                        <x-form.field :label="__('queue.check.phone')" :hint="__('queue.check.phone_hint')" for="hp">
                            <input
                                class="in"
                                id="hp"
                                type="tel"
                                name="hp"
                                inputmode="tel"
                                autocomplete="tel"
                                value="{{ old('hp') }}"
                                required
                            >
                        </x-form.field>
                    </div>

                    <div class="check-submit">
                        <x-ui.button type="submit" variant="accent">{{ __('queue.check.submit') }}</x-ui.button>
                    </div>
                </form>
            </div>

            <aside class="check-stub" aria-label="{{ __('queue.check.preview_aria') }}">
                <span class="check-stub-label">{{ __('queue.check.preview_label') }}</span>
                <span class="check-stub-number-label">{{ __('queue.check.preview_number_label') }}</span>
                <strong class="check-stub-number" aria-hidden="true">&mdash;</strong>
                <p class="check-stub-note">{{ __('queue.check.preview_note') }}</p>
                <span class="check-stub-status">
                    <span class="check-stub-dot" aria-hidden="true"></span>
                    {{ __('queue.check.preview_status') }}
                </span>
            </aside>
        </div>

        <p class="check-help">
            @if ($instansi && $telepon)
                {{ __('queue.check.help', ['instansi' => $instansi, 'telepon' => $telepon]) }}
            @else
                {{ __('queue.check.help_fallback') }}
            @endif
            <a href="{{ route('register') }}">{{ __('queue.check.help_link') }}</a>.
        </p>
    </main>

    <x-site-footer />
</x-layout.app>
