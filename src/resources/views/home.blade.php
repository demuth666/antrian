<x-layout.app
    :title="__('landing.title')"
    :description="__('landing.description')"
    :skip-text="__('common.skip_to_content')"
    font-set="public"
    body-class="landing"
>
    <x-site-header
        :links="[
            ['label' => __('landing.nav.services'), 'href' => '#layanan'],
            ['label' => __('landing.nav.audience'), 'href' => '#untuk-siapa'],
            ['label' => __('landing.nav.steps'), 'href' => '#cara'],
            ['label' => __('landing.nav.check'), 'href' => route('check-queue')],
            ['label' => __('landing.nav.faq'), 'href' => '#faq'],
        ]"
    >
        <x-ui.button :href="route('register')" variant="accent" size="sm">{{ __('landing.cta.register_short') }}</x-ui.button>
    </x-site-header>

    <x-antrian.banner />

    <main id="isi">
        <x-landing.hero />
        <x-landing.features />
        <x-landing.service-section />
        <x-landing.audience-section />
        <x-landing.steps-section />
        <x-landing.faq-section />
        <x-landing.final-cta />
    </main>

    <x-site-footer />
</x-layout.app>
