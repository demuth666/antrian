<x-layout.panel :title="__('admin.services.title')">
    <div class="panel-head">
        <div>
            <h1>{{ __('admin.services.heading') }}</h1>
            <p class="lead">{{ __('admin.services.lead') }}</p>
        </div>

        <div class="cta">
            <x-ui.button :href="route('admin.services.create')" variant="accent">
                <x-icon name="plus" />
                {{ __('admin.services.add') }}
            </x-ui.button>
        </div>
    </div>

    @if ($layanans->isEmpty())
        <div class="table-wrap">
            <p class="empty">{{ __('admin.services.empty') }}</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table is-stacked" aria-label="{{ __('admin.services.table_aria') }}">
                <thead>
                    <tr>
                        <th scope="col">{{ __('admin.services.headers.name') }}</th>
                        <th scope="col">{{ __('admin.services.headers.prefix') }}</th>
                        <th scope="col" class="num">{{ __('admin.services.headers.counters') }}</th>
                        <th scope="col">{{ __('admin.services.headers.status') }}</th>
                        <th scope="col">{{ __('admin.services.headers.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($layanans as $layanan)
                        <tr>
                            <td class="cell-primary"><strong>{{ $layanan->nama }}</strong></td>
                            <td data-label="{{ __('admin.services.headers.prefix') }}"><span class="prefix">{{ $layanan->prefix }}</span></td>
                            <td class="num" data-label="{{ __('admin.services.headers.counters') }}">{{ $layanan->lokets_count }}</td>
                            <td data-label="{{ __('admin.services.headers.status') }}"><x-admin.status :active="$layanan->aktif" /></td>
                            <td class="cell-actions">
                                <div class="row-actions">
                                    <a class="row-action" href="{{ route('admin.services.edit', ['layanan' => $layanan]) }}">{{ __('common.edit') }}</a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.services.destroy', ['layanan' => $layanan]) }}"
                                        data-confirm="{{ __('admin.services.confirm_message', ['name' => $layanan->nama]) }}"
                                        data-confirm-title="{{ __('admin.services.confirm_title') }}"
                                        data-confirm-message="{{ __('admin.services.confirm_message', ['name' => $layanan->nama]) }}"
                                        data-confirm-button="{{ __('admin.services.confirm_button') }}"
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
