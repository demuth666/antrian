@props([
    'lokets' => [],
])

<aside class="ticket-board noprint" aria-label="{{ __('queue.now_serving.aria') }}" aria-live="polite">
    <div class="ticket-board-head">
        <x-ui.kicker>{{ __('queue.now_serving.kicker') }}</x-ui.kicker>
        <span class="ticket-board-updated">{{ __('queue.now_serving.note') }}</span>
    </div>

    @if (count($lokets))
        <div class="ticket-board-grid">
            @foreach ($lokets as $loket)
                <div class="ticket-board-row" data-loket-id="{{ $loket['id'] }}">
                    <span>{{ $loket['nama'] }}</span>
                    <b @if ($loket['nomor'] !== null) data-nomor="{{ $loket['nomor'] }}" @endif>{{ $loket['nomor_tampil'] }}</b>
                </div>
            @endforeach
        </div>
    @else
        <p class="ticket-board-empty">{{ __('queue.now_serving.empty') }}</p>
    @endif
</aside>
