<section class="final-section">
    <div class="wrap">
        <div class="final-panel">
            <div class="final-main">
                <h2>{{ __('landing.final.title') }}</h2>
                <p>{{ __('landing.final.body') }}</p>
                <x-ui.button :href="route('register')" variant="accent">{{ __('landing.final.button') }}</x-ui.button>
            </div>

            <div class="final-stub">
                <span>{{ __('landing.final.stub_label') }}</span>
                <strong>{{ __('landing.final.stub_value') }}</strong>
            </div>
        </div>
    </div>
</section>
