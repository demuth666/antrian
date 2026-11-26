@php
    $pengguna = auth()->user();
    $admin = $pengguna?->role === \App\Enums\UserRole::Admin;
    $tautanPanel = $pengguna
        ? ($admin ? route('admin.dashboard') : route('officer.dashboard'))
        : null;
@endphp

<x-layout.app
    :title="__('errors.forbidden.title')"
    :description="__('errors.forbidden.description')"
    :skip-text="__('common.skip_to_content')"
    robots="noindex"
>
    <x-site-header>
        @if ($tautanPanel)
            <x-ui.button :href="$tautanPanel" variant="outline" size="sm">{{ __('common.back_to_panel') }}</x-ui.button>
        @else
            <x-ui.button :href="route('home')" variant="outline" size="sm">{{ __('common.home') }}</x-ui.button>
        @endif
    </x-site-header>

    <main id="isi" class="wrap" style="padding-top:64px;padding-bottom:96px">
        <x-ui.kicker>{{ __('errors.forbidden.kicker') }}</x-ui.kicker>
        <h1 style="margin:20px 0 24px;font-size:clamp(38px,6vw,64px)">{{ __('errors.forbidden.heading_before') }}<span class="hl">{{ __('errors.forbidden.heading_highlight') }}</span>{{ __('errors.forbidden.heading_after') }}</h1>
        <p class="lead" style="margin-bottom:32px">
            {{ __('errors.forbidden.lead') }}
        </p>

        <div class="cta">
            @if ($tautanPanel)
                <x-ui.button :href="$tautanPanel">{{ __('common.back_to_panel') }}</x-ui.button>
            @endif
            <x-ui.button :href="route('home')" variant="outline">{{ __('common.home') }}</x-ui.button>
        </div>
    </main>

    <x-site-footer />
</x-layout.app>
