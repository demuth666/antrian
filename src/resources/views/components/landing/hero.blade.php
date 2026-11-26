<section class="hero">
    <div class="wrap">
        <div class="hero-copy">
            <x-ui.kicker>{{ __('landing.hero.kicker') }}</x-ui.kicker>
            <h1>{{ __('landing.hero.title_before') }}<span class="hl">{{ __('landing.hero.title_highlight') }}</span>{{ __('landing.hero.title_after') }}</h1>
            <p class="lead">{{ __('landing.hero.lead') }}</p>
            <div class="cta">
                <x-ui.button :href="route('register')" variant="accent">{{ __('landing.cta.register') }}</x-ui.button>
                <x-ui.button :href="route('check-queue')" variant="outline">{{ __('landing.cta.check') }}</x-ui.button>
            </div>
            <p class="hero-help">
                <x-icon name="info" />
                <span>{{ __('landing.hero.help') }}</span>
            </p>
        </div>

        <div class="hero-visual">
            <x-landing.ticket-preview />
        </div>
    </div>
</section>
