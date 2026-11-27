@props([
    'title',
])

@php
    $pengguna = auth()->user();
    $admin = $pengguna?->role === \App\Enums\UserRole::Admin;

    $flashStatus = session('status');
    $flashError = session('error');

    $flashJudul = __('common.flash.success');

    if (filled($flashStatus)) {
        $flashJudul = match (true) {
            str_contains($flashStatus, __('common.flash.keyword_deleted')) => __('common.flash.deleted'),
            str_contains($flashStatus, __('common.flash.keyword_updated')) => __('common.flash.updated'),
            str_contains($flashStatus, __('common.flash.keyword_added')) => __('common.flash.added'),
            default => __('common.flash.success'),
        };
    }

    $tautan = $admin
        ? [
            ['label' => __('admin.nav.overview'), 'href' => route('admin.dashboard'), 'icon' => 'grid', 'active' => request()->routeIs('admin.dashboard')],
            ['label' => __('admin.nav.services'), 'href' => route('admin.services.index'), 'icon' => 'layers', 'active' => request()->routeIs('admin.services.*')],
            ['label' => __('admin.nav.counters'), 'href' => route('admin.counters.index'), 'icon' => 'door', 'active' => request()->routeIs('admin.counters.*')],
            ['label' => __('admin.nav.assistance'), 'href' => route('admin.assistance.index'), 'icon' => 'heart', 'active' => request()->routeIs('admin.assistance.*')],
            ['label' => __('admin.nav.officers'), 'href' => route('admin.officers.index'), 'icon' => 'users', 'active' => request()->routeIs('admin.officers.*')],
        ]
        : [
            ['label' => __('officer.nav.today'), 'href' => route('officer.dashboard'), 'icon' => 'clock', 'active' => request()->routeIs('officer.dashboard')],
            ['label' => __('officer.nav.find_ticket'), 'href' => route('officer.find-ticket'), 'icon' => 'search', 'active' => request()->routeIs('officer.find-ticket*')],
        ];

    $brandUrl = $admin ? route('admin.dashboard') : route('officer.dashboard');
    $panelLabel = $admin ? __('admin.dashboard.kicker') : __('officer.dashboard.kicker');
    $localeSekarang = app()->getLocale();
@endphp

<x-layout.app
    :title="$title.' | '.__('common.panel_title')"
    :skip-text="__('common.skip_to_panel')"
    font-set="public"
    body-class="panel"
    robots="noindex"
>
    <div class="panel-shell">
        <aside class="panel-sidebar">
            <div class="panel-sidebar-top">
                <a class="panel-brand" href="{{ $brandUrl }}">
                    <span class="panel-brand-name">{{ __('common.brand_name') }}</span>
                    <span class="panel-brand-meta">{{ __('common.brand_place') }} &middot; {{ $panelLabel }}</span>
                </a>

                <button
                    class="panel-nav-toggle"
                    type="button"
                    aria-expanded="false"
                    aria-controls="panel-nav"
                    aria-label="{{ __('common.open_menu') }}"
                    data-nav-toggle
                    data-label-open="{{ __('common.menu') }}"
                    data-label-close="{{ __('common.menu_close') }}"
                    data-aria-open="{{ __('common.open_menu') }}"
                    data-aria-close="{{ __('common.close_menu') }}"
                >
                    <span class="panel-toggle-icon" data-icon-open><x-icon name="menu" /></span>
                    <span class="panel-toggle-icon" data-icon-close><x-icon name="close" /></span>
                </button>
            </div>

            <nav id="panel-nav" class="panel-nav" aria-label="{{ __('common.panel_nav') }}">
                @foreach ($tautan as $link)
                    <a
                        href="{{ $link['href'] }}"
                        @if ($link['active']) aria-current="page" @endif
                    >
                        <x-icon :name="$link['icon']" />
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach

                <div class="panel-sidebar-foot">
                    <div class="panel-user">
                        <span class="panel-user-name">{{ $pengguna?->name }}</span>
                        <span class="panel-user-meta">
                            {{ $pengguna?->role?->label() }}@if ($pengguna?->loket) &middot; {{ $pengguna->loket->nama }}@endif
                        </span>
                    </div>

                    <div class="panel-tools">
                        <div class="panel-langs" role="group" aria-label="{{ __('common.language_switch') }}">
                            @foreach (config('app.available_locales', ['id', 'en']) as $locale)
                                <a
                                    href="{{ route('language.switch', ['locale' => $locale]) }}"
                                    hreflang="{{ $locale }}"
                                    lang="{{ $locale }}"
                                    title="{{ __('common.languages.'.$locale) }}"
                                    aria-label="{{ __('common.languages.'.$locale) }}"
                                    @class(['panel-lang', 'is-active' => $localeSekarang === $locale])
                                    @if ($localeSekarang === $locale) aria-current="true" @endif
                                >{{ strtoupper($locale) }}</a>
                            @endforeach
                        </div>

                        <x-theme-toggle />
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="panel-logout" type="submit">
                            <x-icon name="logout" />
                            <span>{{ __('common.logout') }}</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <div class="panel-content">
            <main id="isi" class="panel-main">
                @if ($flashStatus || $flashError)
                    <div class="toast-stack">
                        @if ($flashStatus)
                            <x-ui.toast variant="success" :title="$flashJudul">{{ $flashStatus }}</x-ui.toast>
                        @endif

                        @if ($flashError)
                            <x-ui.toast variant="danger" :title="__('common.flash.error')">{{ $flashError }}</x-ui.toast>
                        @endif
                    </div>
                @endif

                <x-ui.modal
                    id="konfirmasi"
                    :title="__('common.flash.confirm_title')"
                    :description="__('common.flash.confirm_description')"
                    variant="danger"
                    data-default-title="{{ __('common.flash.confirm_title') }}"
                    data-default-message="{{ __('common.flash.confirm_description') }}"
                    data-default-button="{{ __('common.flash.confirm_accept') }}"
                >
                    <div class="modal-actions">
                        <x-ui.button type="button" variant="outline" data-modal-close>{{ __('common.cancel') }}</x-ui.button>
                        <x-ui.button type="button" variant="danger" data-confirm-accept>{{ __('common.flash.confirm_accept') }}</x-ui.button>
                    </div>
                </x-ui.modal>

                {{ $slot }}
            </main>

            <x-site-footer />
        </div>
    </div>
</x-layout.app>
