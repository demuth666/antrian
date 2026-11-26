<x-layout.panel :title="__('admin.assistance.title')">
    <div class="panel-head">
        <div>
            <h1>{{ __('admin.assistance.heading') }}</h1>
            <p class="lead">{{ __('admin.assistance.lead') }}</p>
        </div>

        <div class="cta">
            <x-ui.button :href="route('admin.assistance.create')" variant="accent">
                <x-icon name="plus" />
                {{ __('admin.assistance.add') }}
            </x-ui.button>
        </div>
    </div>

    @if ($bantuans->isEmpty())
        <div class="table-wrap">
            <p class="empty">{{ __('admin.assistance.empty') }}</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table is-stacked" aria-label="{{ __('admin.assistance.table_aria') }}">
                <thead>
                    <tr>
                        <th scope="col">{{ __('admin.assistance.headers.name') }}</th>
                        <th scope="col">{{ __('admin.assistance.headers.code') }}</th>
                        <th scope="col" class="num">{{ __('admin.assistance.headers.used') }}</th>
                        <th scope="col">{{ __('admin.assistance.headers.status') }}</th>
                        <th scope="col">{{ __('admin.assistance.headers.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bantuans as $bantuan)
                        <tr>
                            <td class="cell-primary"><strong>{{ $bantuan->nama }}</strong></td>
                            <td data-label="{{ __('admin.assistance.headers.code') }}"><code class="code">{{ $bantuan->kode }}</code></td>
                            <td class="num" data-label="{{ __('admin.assistance.headers.used') }}">{{ $bantuan->antrians_count }}</td>
                            <td data-label="{{ __('admin.assistance.headers.status') }}"><x-admin.status :active="$bantuan->aktif" /></td>
                            <td class="cell-actions">
                                <div class="row-actions">
                                    <a class="row-action" href="{{ route('admin.assistance.edit', ['bantuan' => $bantuan]) }}">{{ __('common.edit') }}</a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.assistance.destroy', ['bantuan' => $bantuan]) }}"
                                        data-confirm="{{ __('admin.assistance.confirm_message', ['name' => $bantuan->nama]) }}"
                                        data-confirm-title="{{ __('admin.assistance.confirm_title') }}"
                                        data-confirm-message="{{ __('admin.assistance.confirm_message', ['name' => $bantuan->nama]) }}"
                                        data-confirm-button="{{ __('admin.assistance.confirm_button') }}"
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
