@props([
    'links' => [],
    'label' => null,
    'brandUrl' => null,
])

@php
    $brandUrl ??= route('home');
    $menuId = 'navigasi-utama';
    $localeSekarang = app()->getLocale();
    $label ??= __('common.main_nav');
@endphp

<header class="nav">
    <div class="bar">
        <a class="brand" href="{{ $brandUrl }}" aria-label="{{ __('common.brand') }}">
            <span class="brand-name">{{ __('common.brand_name') }}</span>
            <span class="brand-place">{{ __('common.brand_place') }}</span>
        </a>

        <nav id="{{ $menuId }}" aria-label="{{ $label }}">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
            @endforeach

            <div class="lang-switch" role="group" aria-label="{{ __('common.language_switch') }}">
                @foreach (config('app.available_locales', ['id', 'en']) as $locale)
                    <a
                        href="{{ route('language.switch', ['locale' => $locale]) }}"
                        hreflang="{{ $locale }}"
                        lang="{{ $locale }}"
                        title="{{ __('common.languages.'.$locale) }}"
                        aria-label="{{ __('common.languages.'.$locale) }}"
                        @class(['lang-option', 'is-active' => $localeSekarang === $locale])
                        @if ($localeSekarang === $locale) aria-current="true" @endif
                    >{{ strtoupper($locale) }}</a>
                @endforeach
            </div>

            {{ $slot }}
        </nav>

        <div class="nav-actions">
            <x-theme-toggle />

            <button
                class="nav-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="{{ $menuId }}"
                aria-label="{{ __('common.open_menu') }}"
                data-nav-toggle
                data-label-open="{{ __('common.menu') }}"
                data-label-close="{{ __('common.menu_close') }}"
                data-aria-open="{{ __('common.open_menu') }}"
                data-aria-close="{{ __('common.close_menu') }}"
            >
                <span class="nav-toggle-lines" aria-hidden="true"></span>
                <span data-nav-label>{{ __('common.menu') }}</span>
            </button>
        </div>
    </div>
</header>
