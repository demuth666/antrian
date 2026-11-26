<section id="layanan" class="service">
    <div class="wrap">
        <div class="service-copy">
            <x-ui.kicker>{{ __('landing.service.kicker') }}</x-ui.kicker>
            <h2>{{ __('landing.service.heading') }}</h2>
            <p class="lead">{{ __('landing.service.lead') }}</p>
            <x-ui.button href="#cara" variant="outline">{{ __('landing.cta.view_steps') }}</x-ui.button>
        </div>

        <div class="board" role="group" aria-label="{{ __('landing.service.board.aria') }}">
            <div class="board-head">
                <span class="board-label">
                    <span class="board-dot" aria-hidden="true"></span>
                    {{ __('landing.service.board.label') }}
                </span>
                <span class="board-time">10.32</span>
            </div>

            <div class="board-now">
                <p>{{ __('landing.service.board.now') }}</p>
                <strong>A-013</strong>
                <span>{{ __('landing.service.board.counter') }}</span>
            </div>

            <div class="board-rows">
                @foreach (__('landing.service.board.rows') as $row)
                    <div @class(['board-row', 'is-now' => $row['now']])>
                        <span class="board-counter">{{ $row['counter'] }}</span>
                        <span class="board-service">{{ $row['name'] }}</span>
                        <span class="board-number">{{ $row['number'] }}</span>
                    </div>
                @endforeach
            </div>

            <p class="board-foot">{{ __('landing.service.board.sample') }}</p>
        </div>
    </div>
</section>
