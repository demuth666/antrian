<section id="faq" class="faq">
    <div class="wrap">
        <div class="faq-head">
            <x-ui.kicker>{{ __('landing.faq.kicker') }}</x-ui.kicker>
            <h2>{{ __('landing.faq.heading') }}</h2>
        </div>

        <div class="faq-list">
            @foreach (__('landing.faq.items') as $item)
                <x-landing.faq-item :question="$item['question']">
                    {{ $item['answer'] }}
                </x-landing.faq-item>
            @endforeach
        </div>
    </div>
</section>
