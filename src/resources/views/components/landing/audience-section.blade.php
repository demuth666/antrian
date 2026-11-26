<section id="untuk-siapa" class="audience">
    <div class="wrap">
        <div class="audience-head">
            <x-ui.kicker>{{ __('landing.audience.kicker') }}</x-ui.kicker>
            <h2>{{ __('landing.audience.heading') }}</h2>
        </div>

        <div class="audience-list">
            <x-landing.audience-card icon="wheel" :title="__('landing.audience.disability_title')">
                {{ __('landing.audience.disability_body') }}
            </x-landing.audience-card>

            <x-landing.audience-card icon="cane" :title="__('landing.audience.elderly_title')">
                {{ __('landing.audience.elderly_body') }}
            </x-landing.audience-card>

            <x-landing.audience-card icon="heart" :title="__('landing.audience.pregnancy_title')">
                {{ __('landing.audience.pregnancy_body') }}
            </x-landing.audience-card>

            <x-landing.audience-card icon="shield" :title="__('landing.audience.vulnerable_title')">
                {{ __('landing.audience.vulnerable_body') }}
            </x-landing.audience-card>
        </div>
    </div>
</section>
