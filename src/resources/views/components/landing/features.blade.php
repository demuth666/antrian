<section class="features">
    <div class="wrap">
        <div class="features-grid">
            <x-landing.feature-card icon="check" :title="__('landing.features.assistance_title')">
                {{ __('landing.features.assistance_body') }}
            </x-landing.feature-card>

            <x-landing.feature-card icon="clock" :title="__('landing.features.queue_title')">
                {{ __('landing.features.queue_body') }}
            </x-landing.feature-card>

            <x-landing.feature-card icon="heart" :title="__('landing.features.representative_title')">
                {{ __('landing.features.representative_body') }}
            </x-landing.feature-card>
        </div>
    </div>
</section>
