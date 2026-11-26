<x-layout.panel :title="__('admin.officers.title')">
    <div class="panel-head">
        <div>
            <h1>{{ __('admin.officers.heading') }}</h1>
            <p class="lead">{{ __('admin.officers.lead') }}</p>
        </div>

        <div class="cta">
            <x-ui.button :href="route('admin.officers.create')" variant="accent">
                <x-icon name="plus" />
                {{ __('admin.officers.add') }}
            </x-ui.button>
        </div>
    </div>

    @if ($petugas->isEmpty())
        <div class="table-wrap">
            <p class="empty">{{ __('admin.officers.empty') }}</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table is-stacked" aria-label="{{ __('admin.officers.table_aria') }}">
                <thead>
                    <tr>
                        <th scope="col">{{ __('admin.officers.headers.name') }}</th>
                        <th scope="col">{{ __('admin.officers.headers.email') }}</th>
                        <th scope="col">{{ __('admin.officers.headers.counter') }}</th>
                        <th scope="col">{{ __('admin.officers.headers.status') }}</th>
                        <th scope="col">{{ __('admin.officers.headers.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($petugas as $akun)
                        <tr>
                            <td class="cell-primary"><strong>{{ $akun->name }}</strong></td>
                            <td data-label="{{ __('admin.officers.headers.email') }}"><span class="email">{{ $akun->email }}</span></td>
                            <td data-label="{{ __('admin.officers.headers.counter') }}">{{ $akun->loket?->nama ?? __('common.not_assigned') }}</td>
                            <td data-label="{{ __('admin.officers.headers.status') }}"><x-admin.status :active="$akun->aktif" /></td>
                            <td class="cell-actions">
                                <div class="row-actions">
                                    <a class="row-action" href="{{ route('admin.officers.edit', ['petugas' => $akun]) }}">{{ __('common.edit') }}</a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.officers.destroy', ['petugas' => $akun]) }}"
                                        data-confirm="{{ __('admin.officers.confirm_message', ['name' => $akun->name]) }}"
                                        data-confirm-title="{{ __('admin.officers.confirm_title') }}"
                                        data-confirm-message="{{ __('admin.officers.confirm_message', ['name' => $akun->name]) }}"
                                        data-confirm-button="{{ __('admin.officers.confirm_button') }}"
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
