@props([
    'antrian',
    'open' => false,
])

@php
    $ulasan = $antrian->ulasan;
    $opsiRating = __('review.rating_options');
    $modalId = 'ulasan-'.$antrian->kode;
    $judulId = $modalId.'-judul';
    $teksId = $modalId.'-teks';
    $komentarId = $modalId.'-komentar';
    $bukaOtomatis = $open || (! $ulasan && ($errors->has('rating') || $errors->has('komentar')));
@endphp

<aside class="review-invite" aria-labelledby="{{ $modalId }}-ajakan">
    <span @class(['review-invite-icon', 'is-sent' => $ulasan]) aria-hidden="true">
        <x-icon :name="$ulasan ? 'check' : 'star'" />
    </span>

    <div class="review-invite-copy">
        <p class="review-invite-kicker">{{ __('review.kicker') }}</p>

        @if ($ulasan)
            <p class="review-invite-title" id="{{ $modalId }}-ajakan">{{ __('review.sent') }}</p>
            <p class="review-invite-meta">
                <span class="review-stars" role="img" aria-label="{{ __('review.stars', ['count' => $ulasan->rating]) }}">
                    @for ($i = 1; $i <= 5; $i++)
                        <x-icon name="star" @class(['is-filled' => $i <= $ulasan->rating]) />
                    @endfor
                </span>
                <span>{{ $opsiRating[$ulasan->rating] }}</span>
            </p>
        @else
            <p class="review-invite-title" id="{{ $modalId }}-ajakan">{{ __('review.heading') }}</p>
        @endif
    </div>

    <button
        type="button"
        class="review-invite-action"
        data-modal-open="{{ $modalId }}"
        aria-haspopup="dialog"
    >
        {{ $ulasan ? __('review.view') : __('review.invite') }}
        <x-icon name="chevron-right" />
    </button>
</aside>

<dialog
    id="{{ $modalId }}"
    class="modal review-modal"
    aria-labelledby="{{ $judulId }}"
    aria-describedby="{{ $teksId }}"
    tabindex="-1"
    data-modal-focus-dialog
    @if ($bukaOtomatis) data-modal-auto-open @endif
>
    <div class="modal-card review-slip">
        <button class="modal-close" type="button" data-modal-close aria-label="{{ __('common.close') }}">
            <x-icon name="close" />
        </button>

        <header class="review-head">
            <div class="review-head-top">
                <p class="review-kicker">{{ __('review.kicker') }}</p>
                <span class="review-stamp" aria-hidden="true">{{ __('review.stamp') }}</span>
            </div>

            <h2 class="review-title" id="{{ $judulId }}">
                {{ $ulasan ? __('review.thanks_title') : __('review.heading') }}
            </h2>

            <p class="review-lead" id="{{ $teksId }}">
                {{ $ulasan ? __('review.thanks_body') : __('review.lead') }}
            </p>
        </header>

        @if ($ulasan)
            <div class="review-thanks">
                <p class="review-submitted">
                    <span class="review-stars" role="img" aria-label="{{ __('review.stars', ['count' => $ulasan->rating]) }}">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-icon name="star" @class(['is-filled' => $i <= $ulasan->rating]) />
                        @endfor
                    </span>
                    <span>{{ $opsiRating[$ulasan->rating] }}</span>
                </p>

                @if ($ulasan->komentar)
                    <blockquote class="review-quote">{{ $ulasan->komentar }}</blockquote>
                @endif

                <div class="review-actions">
                    <x-ui.button type="button" variant="outline" data-modal-close>{{ __('common.close') }}</x-ui.button>
                </div>
            </div>
        @else
            <form class="review-form" method="POST" action="{{ route('queue.review.store', ['antrian' => $antrian]) }}">
                @csrf

                <fieldset class="review-rating">
                    <legend>{{ __('review.rating_label') }}</legend>

                    <div
                        class="review-rating-row"
                        data-rating
                        data-hint="{{ __('review.rating_hint') }}"
                    >
                        <div class="review-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="review-star" title="{{ $opsiRating[$i] }}">
                                    <input type="radio" name="rating" value="{{ $i }}" @checked((int) old('rating') === $i) required>
                                    <x-icon name="star" />
                                    <span class="sr-only">{{ $opsiRating[$i] }}</span>
                                </label>
                            @endfor
                        </div>

                        <span class="review-caption" data-rating-caption aria-live="polite">{{ __('review.rating_hint') }}</span>
                    </div>

                    @error('rating')
                        <p class="review-error" role="alert">{{ $message }}</p>
                    @enderror
                </fieldset>

                <details class="review-note" @if (old('komentar') || $errors->has('komentar')) open @endif>
                    <summary>
                        {{ __('review.comment_label') }}
                        <span>{{ __('review.comment_optional') }}</span>
                    </summary>

                    <div class="review-comment">
                        <textarea class="in" id="{{ $komentarId }}" name="komentar" rows="2" maxlength="1000" placeholder="{{ __('review.comment_placeholder') }}" aria-label="{{ __('review.comment_label') }}">{{ old('komentar') }}</textarea>

                        @error('komentar')
                            <p class="review-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </details>

                <div class="review-actions">
                    <x-ui.button type="submit" variant="accent">{{ __('review.submit') }}</x-ui.button>
                    <p>{{ __('review.note') }}</p>
                </div>
            </form>
        @endif
    </div>
</dialog>
