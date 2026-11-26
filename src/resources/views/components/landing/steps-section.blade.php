<section id="cara" class="steps">
    <div class="wrap">
        <div class="steps-head">
            <x-ui.kicker>{{ __('landing.steps.kicker') }}</x-ui.kicker>
            <h2>{{ __('landing.steps.heading') }}</h2>
        </div>

        <div class="steps-grid">
            <x-landing.step-card number="01" :title="__('landing.steps.register_title')">
                <p>{{ __('landing.steps.register_body') }}</p>

                <x-slot:actions>
                    <x-ui.button :href="route('register')" variant="accent">{{ __('landing.cta.register') }}</x-ui.button>
                </x-slot:actions>
            </x-landing.step-card>

            <x-landing.step-card number="02" :title="__('landing.steps.assistance_title')">
                <p>{{ __('landing.steps.assistance_body') }}</p>
            </x-landing.step-card>

            <x-landing.step-card number="03" :title="__('landing.steps.come_title')">
                <p>{{ __('landing.steps.come_body') }}</p>
            </x-landing.step-card>
        </div>
    </div>
</section>
