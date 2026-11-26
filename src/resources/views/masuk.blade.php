<x-layout.app
    :title="__('auth.title')"
    :description="__('auth.description')"
    :skip-text="__('common.skip_to_login')"
    robots="noindex"
    font-set="public"
    body-class="landing"
>
    <x-site-header
        :links="[
            ['label' => __('common.home'), 'href' => route('home')],
        ]"
    />

    <main id="isi" class="wrap auth-page">
        <div class="auth-intro">
            <x-ui.kicker>{{ __('auth.kicker') }}</x-ui.kicker>
            <h1>{{ __('auth.heading_before') }}<span class="hl">{{ __('auth.heading_highlight') }}</span>{{ __('auth.heading_after') }}</h1>
            <p class="lead">{{ __('auth.lead') }}</p>

            <div class="auth-note">
                <x-icon name="info" />
                <div>
                    <p>{{ __('auth.no_account_needed') }}</p>
                    <a href="{{ route('home') }}">{{ __('auth.back_home') }}</a>
                </div>
            </div>
        </div>

        <div class="auth-sheet">
            <div class="auth-sheet-head">
                <span>{{ __('common.brand') }}</span>
                <span class="auth-stamp" aria-hidden="true">{{ __('auth.submit') }}</span>
            </div>

            <div class="auth-form">
                @if (session('status'))
                    <x-notice-bar role="status">{{ session('status') }}</x-notice-bar>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="auth-fields">
                        <x-form.field :label="__('auth.email')" for="email">
                            <input
                                class="in"
                                id="email"
                                type="email"
                                name="email"
                                autocomplete="username"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </x-form.field>

                        <x-form.field :label="__('auth.password')" for="password">
                            <input
                                class="in"
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                required
                            >
                        </x-form.field>
                    </div>

                    <label class="auth-check">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        {{ __('auth.remember') }}
                    </label>

                    <div class="auth-submit">
                        <x-ui.button type="submit" variant="accent">{{ __('auth.submit') }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-site-footer />
</x-layout.app>
