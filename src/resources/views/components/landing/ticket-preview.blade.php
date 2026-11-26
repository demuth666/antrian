@php
    $tanggal = now()->locale(app()->getLocale())->translatedFormat('j M Y');
@endphp

<figure class="ticket-figure">
    <div class="ticket" role="group" aria-label="{{ __('landing.hero.ticket.aria') }}">
        <div class="ticket-head">
            <span class="ticket-brand">{{ __('common.brand') }}</span>
            <span class="ticket-stamp">{{ __('landing.hero.ticket.priority') }}</span>
        </div>

        <p class="ticket-label">{{ __('landing.hero.ticket.number_label') }}</p>
        <p class="ticket-no">A-014</p>

        <dl class="ticket-meta">
            <div>
                <dt>{{ __('landing.hero.ticket.name') }}</dt>
                <dd>{{ __('landing.hero.ticket.name_value') }}</dd>
            </div>
            <div>
                <dt>{{ __('landing.hero.ticket.service') }}</dt>
                <dd>{{ __('landing.hero.ticket.service_value') }}</dd>
            </div>
            <div>
                <dt>{{ __('landing.hero.ticket.counter') }}</dt>
                <dd>{{ __('landing.hero.ticket.counter_value') }}</dd>
            </div>
            <div>
                <dt>{{ __('landing.hero.ticket.date') }}</dt>
                <dd>{{ $tanggal }}</dd>
            </div>
        </dl>

        <div class="ticket-perf" aria-hidden="true"></div>

        <div class="ticket-foot">
            <div class="ticket-qr" aria-hidden="true">
                <svg viewBox="0 0 21 21" shape-rendering="crispEdges" focusable="false">
                    <rect width="21" height="21" fill="#FFFFFF" />
                    <g fill="#0E3B3A">
                        <path fill-rule="evenodd" d="M0 0h7v7H0zM1 1h5v5H1zM2 2h3v3H2z" />
                        <path fill-rule="evenodd" d="M14 0h7v7h-7zM15 1h5v5h-5zM16 2h3v3h-3z" />
                        <path fill-rule="evenodd" d="M0 14h7v7H0zM1 15h5v5H1zM2 16h3v3H2z" />
                        <path d="M8 0h1v1H8zM9 0h1v1H9zM10 1h1v1h-1zM8 2h1v1H8zM10 3h1v1h-1zM8 4h1v1H8zM9 5h1v1H9zM10 5h1v1h-1zM8 6h1v1H8zM11 6h1v1h-1zM0 8h1v1H0zM2 8h1v1H2zM4 8h1v1H4zM6 8h1v1H6zM1 9h1v1H1zM3 9h1v1H3zM5 9h1v1H5zM0 10h1v1H0zM2 10h1v1H2zM4 10h1v1H4zM6 10h1v1H6zM1 12h1v1H1zM3 12h1v1H3zM5 12h1v1H5zM8 8h1v1H8zM9 8h1v1H9zM12 8h1v1h-1zM11 9h1v1h-1zM10 10h1v1h-1zM13 10h1v1h-1zM11 11h1v1h-1zM12 12h1v1h-1zM14 8h1v1h-1zM15 8h1v1h-1zM17 9h1v1h-1zM18 8h1v1h-1zM20 9h1v1h-1zM14 10h1v1h-1zM16 10h1v1h-1zM19 10h1v1h-1zM15 11h1v1h-1zM17 11h1v1h-1zM20 12h1v1h-1zM14 12h1v1h-1zM15 13h1v1h-1zM16 12h1v1h-1zM17 13h1v1h-1zM18 12h1v1h-1zM20 13h1v1h-1zM8 14h1v1H8zM9 15h1v1H9zM10 14h1v1h-1zM8 16h1v1H8zM9 17h1v1H9zM10 16h1v1h-1zM8 18h1v1H8zM10 18h1v1h-1zM9 19h1v1H9zM8 20h1v1H8zM10 20h1v1h-1zM12 14h1v1h-1zM13 15h1v1h-1zM14 14h1v1h-1zM15 15h1v1h-1zM16 14h1v1h-1zM17 15h1v1h-1zM18 14h1v1h-1zM19 15h1v1h-1zM20 14h1v1h-1zM12 16h1v1h-1zM13 17h1v1h-1zM14 16h1v1h-1zM16 16h1v1h-1zM17 17h1v1h-1zM18 16h1v1h-1zM20 16h1v1h-1zM12 18h1v1h-1zM13 19h1v1h-1zM14 18h1v1h-1zM15 19h1v1h-1zM16 18h1v1h-1zM17 19h1v1h-1zM18 18h1v1h-1zM19 19h1v1h-1zM20 18h1v1h-1z" />
                    </g>
                </svg>
            </div>

            <div class="ticket-note">
                <span>{{ __('landing.hero.ticket.scan') }}</span>
                <strong class="ticket-status">{{ __('landing.hero.ticket.next') }}</strong>
            </div>
        </div>
    </div>

    <figcaption class="sr-only">{{ __('landing.hero.ticket.caption') }}</figcaption>
</figure>
