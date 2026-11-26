<x-layout.panel :title="__('admin.counters.title')">
    <div class="panel-head">
        <div>
            <h1>{{ __('admin.counters.heading') }}</h1>
            <p class="lead">{{ __('admin.counters.lead') }}</p>
        </div>

        <div class="cta">
            <x-ui.button :href="route('admin.counters.create')" variant="accent">
                <x-icon name="plus" />
                {{ __('admin.counters.add') }}
            </x-ui.button>
        </div>
    </div>

    @if ($lokets->isEmpty())
        <div class="table-wrap">
            <p class="empty">{{ __('admin.counters.empty') }}</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table is-stacked" aria-label="{{ __('admin.counters.table_aria') }}">
                <thead>
                    <tr>
                        <th scope="col">{{ __('admin.counters.headers.name') }}</th>
                        <th scope="col" class="num">{{ __('admin.counters.headers.services') }}</th>
                        <th scope="col" class="num">{{ __('admin.counters.headers.officers') }}</th>
                        <th scope="col">{{ __('admin.counters.headers.status') }}</th>
                        <th scope="col">{{ __('admin.counters.headers.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lokets as $loket)
                        <tr>
                            <td class="cell-primary"><strong>{{ $loket->nama }}</strong></td>
                            <td class="num" data-label="{{ __('admin.counters.headers.services') }}">{{ $loket->layanans_count }}</td>
                            <td class="num" data-label="{{ __('admin.counters.headers.officers') }}">{{ $loket->petugas_count }}</td>
                            <td data-label="{{ __('admin.counters.headers.status') }}"><x-admin.status :active="$loket->aktif" /></td>
                            <td class="cell-actions">
                                <div class="row-actions">
                                    <a class="row-action" href="{{ route('admin.counters.edit', ['loket' => $loket]) }}">{{ __('common.edit') }}</a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.counters.destroy', ['loket' => $loket]) }}"
                                        data-confirm="{{ __('admin.counters.confirm_message', ['name' => $loket->nama]) }}"
                                        data-confirm-title="{{ __('admin.counters.confirm_title') }}"
                                        data-confirm-message="{{ __('admin.counters.confirm_message', ['name' => $loket->nama]) }}"
                                        data-confirm-button="{{ __('admin.counters.confirm_button') }}"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="row-action is-danger" type="submit">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layout.panel>
